<?php

/**
 * PERMISSION AND DIRECTORY FIXER
 * ==============================
 * 
 * This script fixes common permission and directory issues for Laravel hosting
 * Usage: php fix-permissions.php
 */

echo "\n";
echo "🔧 FIXING PERMISSIONS AND DIRECTORIES\n";
echo "=====================================\n";
echo "\n";

// Required directories with their permissions
$directories = [
    'storage' => 0755,
    'storage/app' => 0755,
    'storage/app/public' => 0755,
    'storage/app/private' => 0755,
    'storage/framework' => 0755,
    'storage/framework/cache' => 0755,
    'storage/framework/cache/data' => 0755,
    'storage/framework/sessions' => 0755,
    'storage/framework/views' => 0755,
    'storage/logs' => 0755,
    'bootstrap/cache' => 0755,
    'public/storage' => 0755
];

$created = 0;
$fixed = 0;
$errors = 0;

foreach ($directories as $dir => $permission) {
    $path = __DIR__ . '/' . $dir;
    
    // Create directory if it doesn't exist
    if (!is_dir($path)) {
        if (mkdir($path, $permission, true)) {
            echo "✅ Created: $dir\n";
            $created++;
        } else {
            echo "❌ Failed to create: $dir\n";
            $errors++;
            continue;
        }
    }
    
    // Set permissions (on Windows, this might not work as expected)
    if (DIRECTORY_SEPARATOR === '/') { // Only on Unix-like systems
        $currentPerm = fileperms($path) & 0777;
        if ($currentPerm !== $permission) {
            if (chmod($path, $permission)) {
                echo "✅ Fixed permissions: $dir (set to " . decoct($permission) . ")\n";
                $fixed++;
            } else {
                echo "⚠️  Could not set permissions for: $dir\n";
            }
        } else {
            echo "✅ Permissions OK: $dir\n";
        }
    } else {
        // On Windows, just check if writable
        if (is_writable($path)) {
            echo "✅ Writable: $dir\n";
        } else {
            echo "⚠️  Not writable: $dir (Windows - check manually)\n";
        }
    }
}

echo "\n";

// Check and create storage symlink if needed
echo "🔗 Checking Storage Symlink...\n";
echo "──────────────────────────────\n";

$storagePath = __DIR__ . '/storage/app/public';
$linkPath = __DIR__ . '/public/storage';

if (!file_exists($linkPath)) {
    // Try to create symlink
    if (function_exists('symlink') && DIRECTORY_SEPARATOR === '/') {
        if (symlink($storagePath, $linkPath)) {
            echo "✅ Created storage symlink\n";
        } else {
            echo "❌ Failed to create symlink\n";
        }
    } else {
        echo "⚠️  Cannot create symlink on Windows - will copy directory structure\n";
        
        // On Windows, we'll ensure the directory exists with proper structure
        if (!is_dir($linkPath)) {
            mkdir($linkPath, 0755, true);
        }
        
        // Copy .gitignore if it exists in the source
        $sourceGitignore = $storagePath . '/.gitignore';
        $targetGitignore = $linkPath . '/.gitignore';
        
        if (file_exists($sourceGitignore) && !file_exists($targetGitignore)) {
            copy($sourceGitignore, $targetGitignore);
        }
        
        echo "✅ Storage directory structure ready\n";
    }
} else {
    echo "✅ Storage link/directory already exists\n";
}

echo "\n";

// Create required files
echo "📄 Creating Required Files...\n";
echo "─────────────────────────────\n";

// Create .gitignore files for empty directories
$gitignoreContent = "*\n!.gitignore\n";
$gitignoreDirs = [
    'storage/app/private',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views'
];

foreach ($gitignoreDirs as $dir) {
    $gitignorePath = __DIR__ . '/' . $dir . '/.gitignore';
    if (!file_exists($gitignorePath)) {
        if (file_put_contents($gitignorePath, $gitignoreContent)) {
            echo "✅ Created .gitignore in: $dir\n";
        } else {
            echo "❌ Failed to create .gitignore in: $dir\n";
        }
    }
}

// Create web.config for IIS (Windows hosting)
$webConfigPath = __DIR__ . '/public/web.config';
if (!file_exists($webConfigPath)) {
    $webConfigContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Imported Rule 1" stopProcessing="true">
                    <match url="^(.*)/$" ignoreCase="false" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Redirect" redirectType="Permanent" url="/{R:1}" />
                </rule>
                <rule name="Imported Rule 2" stopProcessing="true">
                    <match url="^" ignoreCase="false" />
                    <conditions>
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
XML;
    
    if (file_put_contents($webConfigPath, $webConfigContent)) {
        echo "✅ Created web.config for IIS hosting\n";
    }
}

echo "\n";

// Summary
echo "📊 SUMMARY\n";
echo "─────────\n";
echo "Directories created: $created\n";
echo "Permissions fixed: $fixed\n";
echo "Errors encountered: $errors\n";

if ($errors === 0) {
    echo "\n✅ All permissions and directories are now properly set up!\n";
} else {
    echo "\n⚠️  Some issues remain - check the errors above\n";
}

echo "\nNext steps:\n";
echo "1. Update hosting-config.php with correct database details\n";
echo "2. Run: php make-hosting-ready.php\n";
echo "\n";