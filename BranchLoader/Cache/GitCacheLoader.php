<?php


namespace Eniams\BranchLoader\Cache;

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
    const HEAD = '/.git/HEAD';
    const COMMIT_EDIT_MESSAGE = '/.git/COMMIT_EDITMSG';
    const CONFIG_FILE = '/.git/config';
    const GIT_LOG_FILE = '/.git/logs/HEAD';

    private $cache;
    private $headFile;
    private $commitEditMessage;
    private $gitLogFile;
    private $configFile;


    /**
     * GitCacheLoader constructor.
     * @param CacheInterface $cache
     * @param $projectDir Project root dir
     */
    public function __construct(CacheInterface $cache, $projectDir)
    {
        $this->cache = $cache;
        $this->headFile = $projectDir.self::HEAD;
        $this->configFile =$projectDir.self::CONFIG_FILE;
        $this->commitEditMessage = $projectDir.self::COMMIT_EDIT_MESSAGE;
        $this->gitLogFile = $projectDir.self::GIT_LOG_FILE;
    }

    /**
     * @return string
     */
    public function getBranchName(): string
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
     * @return string
     */
    public function getLastCommitMessage(): string
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
     * @return array
     */
    public function getLastCommitDetail(): array
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
     * @return array
     */
    public function getLogsFromCache(): array
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
     * @return string
     */
    public function getUrlFromCache(): string
    {
        return $this->cache->get('git.url_repository');
    }
}
