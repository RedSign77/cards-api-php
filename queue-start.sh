#!/bin/bash
# Queue Worker Start Script for Cards Forge API

# Get the directory where this script is located
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Change to the script directory
cd "$SCRIPT_DIR"

echo "Cards Forge API - Queue Worker"
echo "==============================="
echo "Working directory: $SCRIPT_DIR"
echo ""

# Check if we're in Rewardenv
if command -v reward &> /dev/null && [ -d ".reward" ]; then
    echo "Rewardenv detected. Starting queue worker in container..."
    echo ""
    reward shell -c "php artisan queue:work database --sleep=3 --tries=3 --verbose"
else
    echo "Starting queue worker locally..."
    echo ""

    # Check if artisan file exists
    if [ ! -f "artisan" ]; then
        echo "Error: artisan file not found in $SCRIPT_DIR"
        exit 1
    fi

    # Run queue worker with stop-when-empty for cron jobs
    php artisan queue:work database --stop-when-empty --sleep=3 --tries=3 --max-time=50
fi
