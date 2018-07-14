<?php


namespace Eniams\Bundle\GitProfilerBundle\BranchLoader;


/**
 * Class GitFilePath Responsible to retrieve Git files path
 *
 * @package Eniams\BranchLoader
 *
 * @author Smaine Milianni <contact@smaine.me>
 */
/**
 * Class GitFilePath
 *
 * @package Eniams\BranchLoader
 */
final class GitFilePath
{
    const COMMIT_EDIT_MESSAGE = '/.git/COMMIT_EDITMSG';
    const CONFIG_FILE = '/.git/config';
    const GIT_LOG_FILE = '/.git/logs/HEAD';
    const HEAD = '/.git/HEAD';

    private $projectDir;

    /**
     * GitFilePath.
     *
     * @param $projectDir Project root directory
     */
    public function __construct($projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @return string
     */
    public function getHeadPathFile(): string
    {
        return $this->projectDir.self::HEAD;
    }

    /**
     * @return string
     */
    public function getConfigPathFile(): string
    {
        return $this->projectDir.self::CONFIG_FILE;
    }

    /**
     * @return string
     */
    public function getCommitEditMessagePathFile(): string
    {
        return $this->projectDir.self::COMMIT_EDIT_MESSAGE;
    }

    /**
     * @return string
     */
    public function getGitLogPathFile(): string
    {
        return $this->projectDir.self::GIT_LOG_FILE;
    }

    public function __clone()
    {
    }
}
