<?php

namespace Eniams\Bundle\GitProfilerBundle\Tests;

use Eniams\Bundle\GitProfilerBundle\BranchLoader\GitFilePath;

class GitDirectoryFixtures
{
    const CONFIG_FILE = '/.git/config';
    const FIRST_COMMIT = "first commit !";
    const GIT_LOG_CONTENT = '000 c167d99 eniams <contact@smaine.me> 1532281069 +020  commit first commit !';
    const GIT_DIR = __DIR__.'/.git';
    const HEAD_CONTENT = "ref: refs/heads/master";
    const CONFIG_CONTENT = <<<EOD
[core]
repositoryformatversion = 0
filemode = true
bare = false
logallrefupdates = true
[remote \"origin\"]
url = https://github.com/my_account/project.com.git
EOD;


    public static function createGitDirectories()
    {
        $logDirectory = self::GIT_DIR.'/logs/';

        if (!file_exists(self::GIT_DIR)) {
            mkdir(self::GIT_DIR, 0700, true);
        }

        if (!file_exists($logDirectory)) {
            mkdir($logDirectory, 0700, true);
        }

        file_put_contents(__DIR__.GitFilePath::COMMIT_EDIT_MESSAGE, sprintf(self::FIRST_COMMIT));
        file_put_contents(__DIR__.GitFilePath::HEAD, sprintf(self::HEAD_CONTENT));
        file_put_contents(__DIR__.GitFilePath::GIT_LOG_FILE, sprintf(self::GIT_LOG_CONTENT));
        file_put_contents(__DIR__.GitFilePath::CONFIG_FILE, sprintf(self::CONFIG_CONTENT));
    }

    public static function removeGitDirectories()
    {
        self::rrmdir(self::GIT_DIR);
    }

    private static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir."/".$object)) {
                        self::rrmdir($dir."/".$object);
                    } else {
                        unlink($dir."/".$object);
                    }
                }
            }
            rmdir($dir);
        }
    }
}
