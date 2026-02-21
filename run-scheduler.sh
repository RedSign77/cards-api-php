#!/bin/bash

##############################################################################
# Laravel Scheduler Runner with Process Locking
# Prevents duplicate job executions and limits concurrent processes
##############################################################################

# Auto-detect project root (where this script is located)
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="${SCRIPT_DIR}"

# Configuration
PROJECT_NAME="$(basename "${PROJECT_ROOT}")"
LOCK_FILE="/tmp/${PROJECT_NAME}-scheduler.lock"
LOCK_TIMEOUT=60  # seconds
LOG_FILE="${PROJECT_ROOT}/storage/logs/scheduler.log"

# Detect environment (dev vs production)
if [ -f "${PROJECT_ROOT}/.env" ]; then
    APP_ENV=$(grep -E "^APP_ENV=" "${PROJECT_ROOT}/.env" | cut -d '=' -f2 | tr -d '"' | tr -d "'")
else
    APP_ENV="production"
fi

# Change to project directory
cd "${PROJECT_ROOT}" || exit 1

# Function to log messages
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] [${APP_ENV}] $1" >> "${LOG_FILE}"
}

# Function to acquire lock
acquire_lock() {
    local pid

    # Check if lock file exists
    if [ -f "${LOCK_FILE}" ]; then
        pid=$(cat "${LOCK_FILE}")

        # Check if process is still running
        if ps -p "${pid}" > /dev/null 2>&1; then
            log_message "Scheduler already running (PID: ${pid}). Skipping execution."
            return 1
        else
            log_message "Stale lock file found (PID: ${pid}). Removing."
            rm -f "${LOCK_FILE}"
        fi
    fi

    # Create lock file with current PID
    echo $$ > "${LOCK_FILE}"
    return 0
}

# Function to release lock
release_lock() {
    rm -f "${LOCK_FILE}"
}

# Trap to ensure lock is released on exit
trap release_lock EXIT INT TERM

# Main execution
main() {
    # Try to acquire lock
    if ! acquire_lock; then
        exit 0
    fi

    log_message "Starting scheduler run (Project: ${PROJECT_NAME}, Root: ${PROJECT_ROOT})"

    # Run Laravel scheduler (detect if using Docker or native)
    if command -v docker &> /dev/null && docker ps | grep -q "php-fpm"; then
        # Docker environment
        CONTAINER_NAME=$(docker ps --format '{{.Names}}' | grep -E "php-fpm|php" | head -n1)
        log_message "Running in Docker container: ${CONTAINER_NAME}"
        docker exec "${CONTAINER_NAME}" php artisan schedule:run >> "${LOG_FILE}" 2>&1
    else
        # Native PHP environment
        log_message "Running in native PHP environment"
        php artisan schedule:run >> "${LOG_FILE}" 2>&1
    fi

    local exit_code=$?

    if [ ${exit_code} -eq 0 ]; then
        log_message "Scheduler run completed successfully"
    else
        log_message "Scheduler run failed with exit code: ${exit_code}"
    fi

    return ${exit_code}
}

# Execute main function
main
exit $?
