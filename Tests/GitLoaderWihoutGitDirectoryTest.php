<?php

use PHPUnit\Framework\TestCase;

use Eniams\Bundle\GitProfilerBundle\BranchLoader\GitFilePath;
use Eniams\Bundle\GitProfilerBundle\BranchLoader\GitLoader;

class GitLoaderWihoutGitDirectoryTest extends Testcase
{
    protected function setUp()
    {
        $this->gitFilePath = new GitFilePath(__DIR__);
        $this->gitLoader = new GitLoader($this->gitFilePath);
    }

    public function testNoBranchName()
    {
        $this->assertEquals(GitLoader::NO_BRANCH, $this->gitLoader->getBranchName());
    }

    public function testNoLastCommit()
    {
        $this->assertEquals(GitLoader::NO_COMMIT, $this->gitLoader->getLastCommitMessage());
    }

    public function testNoLastCommitDetail()
    {
        $this->assertEmpty($this->gitLoader->getLastCommitDetail());
    }

    public function testNoRemoteRepository()
    {
        $this->assertEquals(GitLoader::NO_REMOTE_REPO, $this->gitLoader->getBaseUrlRepository());
    }
}
