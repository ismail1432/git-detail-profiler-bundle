<?php


namespace Eniams\BranchLoader;


/**
 * Class GitManager
 * @package Eniams\BranchLoader
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
     * @return string
     */
    public function findCurrentBranch()
    {
        if($this->cacheLoader->branchCacheIsValid()) {
            return $this->cacheLoader->getBranchName();
        }

        $branchName = $this->gitLoader->getBranchName();
        $this->cacheLoader->setBranchNameInCache($branchName);

        return $branchName;
    }

    /**
     * @return string
     */
    public function findLastCommitMessage()
    {
        if($this->cacheLoader->lastCommitMessageCacheIsValid()) {
            //return $this->cacheLoader->getLastCommitMessage();
        }

        $message = $this->gitLoader->getLastCommitMessage();
        $this->cacheLoader->setLastCommitMessageInCache($message);

        return $message;
    }

    /**
     * @return array
     * @throws InvalidUrlException
     */
    public function findLastCommitDetail()
    {
        if($this->cacheLoader->gitLogsCacheIsValid()) {
            return $this->cacheLoader->getLastCommitDetail();
        }

        $message = $this->gitLoader->getLastCommitDetail();
        $this->cacheLoader->setLastCommitDetailInCache($message);

        return $message;
    }

    /**
     * @return array|null
     * @throws InvalidUrlException
     */
    public function findLogs()
    {
        if($this->cacheLoader->gitLogsCacheIsValid()) {
            //  return $this->cacheLoader->getLogsFromCache();
        }

        $logs = $this->gitLoader->getLogs();
        $this->cacheLoader->setLogsInCache($logs);

        return $logs;
    }

    /**
     * @return string
     * @throws InvalidUrlException
     */
    public function findUrlRepository()
    {
        if($this->cacheLoader->gitUrlCacheIsValid()) {
            return $this->cacheLoader->getUrlFromCache();
        }

        $url = $this->gitLoader->getBaseUrlRepository();
        $this->cacheLoader->setUrlInCache($url);

        return $url;
    }
}
