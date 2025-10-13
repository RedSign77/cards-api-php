#!/bin/bash
# Queue Worker Start Script for Cards Forge API

echo "Cards Forge API - Queue Worker"
echo "==============================="
echo ""

# Check if we're in Rewardenv
if command -v reward &> /dev/null && [ -d ".reward" ]; then
    echo "Rewardenv detected. Starting queue worker in container..."
    echo ""
    reward shell -c "php artisan queue:work database --sleep=3 --tries=3 --verbose"
else
    echo "Starting queue worker locally..."
    echo ""
    php artisan queue:work database --sleep=3 --tries=3 --verbose
fi
