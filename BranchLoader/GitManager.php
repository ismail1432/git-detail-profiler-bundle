<?php


namespace Eniams\BranchLoader;


class GitManager
{
    private $cacheLoader;
    private $gitLoader;

    public function __construct(GitCacheLoader $cacheLoader, GitLoader $gitLoader)
    {
        $this->cacheLoader = $cacheLoader;
        $this->gitLoader = $gitLoader;
    }

    public function findCurrentBranch()
    {
        if($this->cacheLoader->branchCacheIsValid()) {
            return $this->cacheLoader->getBranchName();
        }

        $branchName = $this->gitLoader->getBranchName();
        $this->cacheLoader->setBranchNameInCache($branchName);

        return $branchName;
    }

    public function findLastCommitMessage()
    {
        if($this->cacheLoader->lastCommitMessageCacheIsValid()) {
            //return $this->cacheLoader->getLastCommitMessage();
        }

        $message = $this->gitLoader->getLastCommitMessage();
        $this->cacheLoader->setLastCommitMessageInCache($message);

        return $message;
    }

    public function findLastCommitDetail()
    {
        if($this->cacheLoader->gitLogsCacheIsValid()) {
            return $this->cacheLoader->getLastCommitDetail();
        }

        $message = $this->gitLoader->getLastCommitDetail();
        $this->cacheLoader->setLastCommitDetailInCache($message);

        return $message;
    }

    public function findLogs()
    {
        if($this->cacheLoader->gitLogsCacheIsValid()) {
            //  return $this->cacheLoader->getLogsFromCache();
        }

        $logs = $this->gitLoader->getLogs();
        $this->cacheLoader->setLogsInCache($logs);

        return $logs;
    }

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
