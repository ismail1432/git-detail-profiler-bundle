<?php


namespace Eniams\Bundle\GitProfilerBundle\DataCollector;

use Eniams\Bundle\GitProfilerBundle\BranchLoader\Cache\GitCacheLoader;
use Eniams\Bundle\GitProfilerBundle\BranchLoader\GitLoader;
use Eniams\Bundle\GitProfilerBundle\BranchLoader\Manager\GitManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class GitDataCollector extends DataCollector
{
    const BaseTemplate = '@GitProfiler/Collector/';
    private $gitLoader;
    private $gitManager;

    public function __construct(GitCacheLoader $gitLoader, GitManager $gitManager)
    {
        $this->gitLoader = $gitLoader;
        $this->gitManager = $gitManager;
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // We add the git informations in the data
        $this->data = [
            'git_branch' => $this->gitManager->findCurrentBranch(),
            'last_commit_message' => $this->gitManager->findLastCommitMessage(),
            'last_commit_details' => $this->gitManager->findLastCommitDetail(),
            'logs' => $this->gitManager->findLogs(),
            'url_repository' => $this->gitManager->findUrlRepository(),
        ];
    }

    public function getName()
    {
        return 'eniams.git_data_collector';
    }

    public function reset()
    {
        $this->data = array();
    }

    //Some helpers to access more easily in the template
    public function getGitBranch()
    {
        return $this->data['git_branch'];
    }

    public function getLastCommitMessage()
    {
        return $this->data['last_commit_message'];
    }

    public function getLastCommitAuthor()
    {
        return $this->data['last_commit_details']['author'] ?? GitLoader::NO_COMMIT;
    }

    public function getLastCommitDate()
    {
        return $this->data['last_commit_details']['date'] ?? GitLoader::NO_COMMIT;
    }

    public function getLogs()
    {
        return $this->data['logs'];
    }

    public function getUrlRepository()
    {
        return $this->data['url_repository'];
    }

    public function getRemoteTemplate()
    {
        $template = GitLoader::NO_REMOTE_REPO === $this->data['url_repository'] ? 'no_remote_repo' : 'remote_repo';

        return self::BaseTemplate."/$template.html.twig";
    }
}
