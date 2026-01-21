<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StorageLinkAlternative extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link-alt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create storage link alternative for shared hosting without symlink support';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = storage_path('app/public');
        $target = public_path('storage');

        // Vérifier si symlink existe déjà
        if (is_link($target)) {
            $this->info('✓ Symbolic link already exists.');
            return 0;
        }

        // Essayer de créer un symlink
        $this->info('Attempting to create symbolic link...');
        try {
            if (@symlink($source, $target)) {
                $this->info('✓ Symbolic link created successfully.');
                return 0;
            }
        } catch (\Exception $e) {
            $this->warn('✗ Symlink failed: ' . $e->getMessage());
            $this->warn('Falling back to directory copy method...');
        }

        // Fallback: créer le dossier
        if (!File::exists($target)) {
            File::makeDirectory($target, 0755, true);
            $this->info('✓ Created public/storage directory.');
        } else {
            $this->info('✓ public/storage directory already exists.');
        }

        // Copier les fichiers
        if (File::exists($source)) {
            $this->info('Copying files from storage/app/public to public/storage...');
            
            try {
                File::copyDirectory($source, $target);
                $this->info('✓ Files copied successfully to public/storage.');
                
                // Créer un fichier .htaccess pour la sécurité
                $this->createHtaccess($target);
                
                $this->newLine();
                $this->info('Storage link alternative created successfully!');
                $this->warn('Note: Run this command after each deployment to sync files.');
                
            } catch (\Exception $e) {
                $this->error('✗ Failed to copy files: ' . $e->getMessage());
                return 1;
            }
        } else {
            $this->warn('Source directory does not exist: ' . $source);
            $this->info('Creating source directory...');
            File::makeDirectory($source, 0755, true);
        }

        return 0;
    }

    /**
     * Create .htaccess file to prevent PHP execution
     */
    protected function createHtaccess($target)
    {
        $htaccess = $target . '/.htaccess';
        
        $content = <<<'HTACCESS'
# Prevent PHP execution in storage directory
<FilesMatch "\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$">
    Order Deny,Allow
    Deny from All
</FilesMatch>

# Disable script execution
Options -ExecCGI
AddHandler cgi-script .php .php3 .php4 .php5 .phtml .pl .py .jsp .asp .sh .cgi

# Protect .htaccess
<Files .htaccess>
    Order Allow,Deny
    Deny from all
</Files>
HTACCESS;

        File::put($htaccess, $content);
        $this->info('✓ Created security .htaccess file.');
    }
}
