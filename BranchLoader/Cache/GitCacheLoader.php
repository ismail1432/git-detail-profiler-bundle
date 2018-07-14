<?php


namespace Eniams\Bundle\GitProfilerBundle\BranchLoader\Cache;

use Eniams\BranchLoader\GitFilePath;
use Eniams\BranchLoader\InvalidUrlException;
use Psr\SimpleCache\CacheInterface;


/**
 * Class GitCacheLoader Responsible to get and set information in cache
 *
 * @package Eniams\BranchLoader\Cache
 *
 * @author Smaine Milianni <contact@smaine.me>
 */
class GitCacheLoader
{

    private $cache;
    private $headFile;
    private $commitEditMessage;
    private $gitLogFile;
    private $configFile;


    /**
     * GitCacheLoader constructor.
     * @param CacheInterface $cache
     * @param GitFilePath $filePath
     */
    public function __construct(CacheInterface $cache, GitFilePath $filePath)
    {
        $this->cache = $cache;
        $this->headFile = $filePath->getHeadPathFile();
        $this->configFile = $filePath->getConfigPathFile();
        $this->commitEditMessage = $filePath->getCommitEditMessagePathFile();
        $this->gitLogFile = $filePath->getGitLogPathFile();
    }

    /**
     * @return string|null
     *
     * @throws InvalidUrlException
     */
    public function getBranchName(): ?string
    {
        return $this->cache->get('git.branch_name');
    }

    /**
     * @return bool
     */
    public function branchCacheIsValid(): bool
    {
        return $this->cache->has('git.time_store_branch_name') ? $this->cache->get('git.time_store_branch_name') === filemtime($this->headFile) : false;
    }

    /**
     * @param string $branchName
     */
    public function setBranchNameInCache(string $branchName): void
    {
        $this->cache->set('git.time_store_branch_name', filemtime($this->headFile));
        $this->cache->set('git.branch_name', $branchName);
    }

    /**
     * @return bool
     */
    public function lastCommitMessageCacheIsValid(): bool
    {
        return $this->cache->has('git.time_last_commit_message') ? $this->cache->get('git.time_last_commit_message') === filemtime($this->commitEditMessage) : false;
    }

    /**
     * @return string|null
     */
    public function getLastCommitMessage(): ?string
    {
        return $this->cache->get('git.last_commit_message');

    }

    /**
     * @param string $message
     */
    public function setLastCommitMessageInCache(string $message): void
    {
        $this->cache->set('git.time_last_commit_message', filemtime($this->commitEditMessage));
        $this->cache->set('git.last_commit_message', $message);
    }

    /**
     * @return array|null
     */
    public function getLastCommitDetail(): ?array
    {
        return $this->cache->get('git.last_commit_detail');

    }

    /**
     * @param array $details
     */
    public function setLastCommitDetailInCache($details): void
    {
        $this->cache->set('git.time_last_commit_detail', filemtime($this->gitLogFile));
        $this->cache->set('git.last_commit_detail', $details);
    }

    /**
     * @param array $logs
     */
    public function setLogsInCache($logs): void
    {
        $this->cache->set('git.time_logs', filemtime($this->gitLogFile));
        $this->cache->set('git.logs', $logs);
    }

    /**
     * @return array|null
     */
    public function getLogsFromCache(): ?array
    {
        return $this->cache->get('git.logs');
    }

    /**
     * @return bool
     */
    public function gitLogsCacheIsValid(): bool
    {
        return $this->cache->has('git.time_logs') ? $this->cache->get('git.time_logs') === filemtime($this->gitLogFile) : false;
    }

    /**
     * @param string $url
     */
    public function setUrlInCache(string $url): bool
    {
        $this->cache->set('git.time_url_repository', filemtime($this->configFile));
        $this->cache->set('git.url_repository', $url);
    }

    /**
     * @return bool
     */
    public function gitUrlCacheIsValid(): bool
    {
        return $this->cache->has('git.time_url_repository') ? $this->cache->get('git.time_url_repository') === filemtime($this->configFile) : false;
    }

    /**
     * @return string|null
     */
    public function getUrlFromCache(): ?string
    {
        return $this->cache->get('git.url_repository');
    }
}
