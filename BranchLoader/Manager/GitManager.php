<?php


namespace Eniams\Bundle\GitProfilerBundle\BranchLoader\Manager;

use Eniams\Bundle\GitProfilerBundle\BranchLoader\Cache\GitCacheLoader;
use Eniams\Bundle\GitProfilerBundle\BranchLoader\GitLoader;
use Eniams\Bundle\GitProfilerBundle\BranchLoader\Exception\InvalidUrlException;

/**
 * Class GitManager Responsible to retrieve Git information from cache or application
 *
 * @package Eniams\BranchLoader
 *
 * @author Smaine Milianni <contact@smaine.me>
 */
class GitManager
{
    /**
     * @var GitCacheLoader
     */
    private $cacheLoader;
    /**
     * @var GitLoader
     */
    private $gitLoader;

    /**
     * GitManager constructor.
     * @param GitCacheLoader $cacheLoader
     * @param GitLoader $gitLoader
     */
    public function __construct(GitCacheLoader $cacheLoader, GitLoader $gitLoader)
    {
        $this->cacheLoader = $cacheLoader;
        $this->gitLoader = $gitLoader;
    }

    /**
     * @return string Retrieve Current Branch
     *
     * @throws InvalidUrlException
     */
    public function findCurrentBranch(): string
    {
        if ($this->cacheLoader->branchCacheIsValid()) {
            return $this->cacheLoader->getBranchName();
        }

        $branchName = $this->gitLoader->getBranchName();
        $this->cacheLoader->setBranchNameInCache($branchName);

        return $branchName;
    }

    /**
     * @return string Retrieve last commit message
     */
    public function findLastCommitMessage(): string
    {
        if ($this->cacheLoader->lastCommitMessageCacheIsValid()) {
            return $this->cacheLoader->getLastCommitMessage();
        }

        $message = $this->gitLoader->getLastCommitMessage();
        $this->cacheLoader->setLastCommitMessageInCache($message);

        return $message;
    }

    /**
     * @return array Retrieve details of the last commit
     *
     * @throws InvalidUrlException
     */
    public function findLastCommitDetail()
    {
        if ($this->cacheLoader->gitLogsCacheIsValid()) {
            return $this->cacheLoader->getLastCommitDetail();
        }

        $message = $this->gitLoader->getLastCommitDetail();
        $this->cacheLoader->setLastCommitDetailInCache($message);

        return $message;
    }

    /**
     * @return array|null Retrieve Git details
     *
     * @throws InvalidUrlException
     */
    public function findLogs()
    {
        if ($this->cacheLoader->gitLogsCacheIsValid()) {
            return $this->cacheLoader->getLogsFromCache();
        }

        $logs = $this->gitLoader->getLogs();
        $this->cacheLoader->setLogsInCache($logs);

        return $logs;
    }

    /**
     * @return string Retrieve the url of the repository
     *
     * @throws InvalidUrlException
     */
    public function findUrlRepository()
    {
        if ($this->cacheLoader->gitUrlCacheIsValid()) {
            return $this->cacheLoader->getUrlFromCache();
        }

        $url = $this->gitLoader->getBaseUrlRepository();
        $this->cacheLoader->setUrlInCache($url);

        return $url;
    }
}
