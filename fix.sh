#!/bin/bash

TARGET_DIR="app/Http/Controllers"

echo "======================================"
echo " Laravel 10 Controller Import Fixer"
echo "======================================"
echo "Scanning directory: $TARGET_DIR"
echo ""

# Loop through all PHP files in the controllers directory
find "$TARGET_DIR" -type f -name "*.php" | while read file; do
    echo "🔧 Processing: $file"

    # Remove invalid imports
    sed -i '/^use DB;/d' "$file"
    sed -i '/^use Session;/d' "$file"
    sed -i '/^use Auth;/d' "$file"
    sed -i '/^use Mail;/d' "$file"
    sed -i '/^use Excel;/d' "$file"
    sed -i '/^use Date;/d' "$file"
    sed -i '/^use DateTime;/d' "$file"

    # Remove duplicate facades
    sed -i '/^use Illuminate\\\Support\\\Facades\\\DB;/d' "$file"
    sed -i '/^use Illuminate\\\Support\\\Facades\\\Session;/d' "$file"
    sed -i '/^use Illuminate\\\Support\\\Facades\\\Auth;/d' "$file"
    sed -i '/^use Illuminate\\\Support\\\Facades\\\Mail;/d' "$file"
    sed -i '/^use Maatwebsite\\\Excel\\\Facades\\\Excel;/d' "$file"
    sed -i '/^use Carbon\\\Carbon;/d' "$file"

    # Insert correct imports AFTER the namespace line
    sed -i "/^namespace App\\\\Http\\\\Controllers;/a \
use Illuminate\\\Support\\\Facades\\\DB;\nuse Illuminate\\\Support\\\Facades\\\Session;\nuse Illuminate\\\Support\\\Facades\\\Auth;\nuse Illuminate\\\Support\\\Facades\\\Mail;\nuse Maatwebsite\\\Excel\\\Facades\\\Excel;\nuse Carbon\\\Carbon;" "$file"

    echo "✔ Fixed"
    echo ""
done

echo "======================================"
echo " Import Fix Completed Successfully!"
echo "======================================"
