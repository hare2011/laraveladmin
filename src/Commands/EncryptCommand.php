<?php

namespace Runhare\Admin\Commands;

use Runhare\Admin\Facades\Admin;
use Illuminate\Console\Command;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RegexIterator;
use \FilesystemIterator;
use \RecursiveRegexIterator;
class EncryptCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:encrypt {file* : file or directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'encrypt the php code';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (version_compare(PHP_VERSION, 7, '<'))
            die("PHP must later than version 7.0\n");
        if (php_sapi_name() !== 'cli')
            die("Must run in cli mode\n");
        if (!extension_loaded('tonyenc'))
            die("The extension: 'tonyenc' not loaded\n");
        if(!$this->hasArgument('file')) {
            die("\nusage: php artisan admin:encrypt file.php ...     encrypt the php file(s) or directory(s)\n\n");
        }

        $arguments = $this->argument('file');

        foreach ($arguments as $fileName) {
            $fileName = app_path(ucfirst($fileName));
            if (is_file($fileName)) {
                $this->encrypt($fileName);
            } elseif (is_dir($fileName)) {
                $DirectoriesIt = new RecursiveDirectoryIterator($fileName, FilesystemIterator::SKIP_DOTS);
                $AllIt         = new RecursiveIteratorIterator($DirectoriesIt);
                $it            = new RegexIterator($AllIt, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
                foreach ($it as $v)
                    $this->encrypt($v[0]);
            } else {
                echo "Unknowing file: '$fileName'\n";
            }
        }
    }

    function encrypt($file)
    {
        if ($fp = fopen($file, 'rb+') and $fileSize = filesize($file))
        {
            $data = tonyenc_encode(fread($fp, $fileSize));
            if ($data !== false) {
                if (file_put_contents($file, '') !== false) {
                    rewind($fp);
                    fwrite($fp, $data);
                }
            }
            fclose($fp);
        }
    }
}
