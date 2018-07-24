<?php

use PHPUnit\Framework\TestCase;
use Eniams\Bundle\GitProfilerBundle\Tests\GitDirectoryFixtures;
use Eniams\Bundle\GitProfilerBundle\BranchLoader\GitFilePath;
use Eniams\Bundle\GitProfilerBundle\BranchLoader\GitLoader;

class GitLoaderTest extends Testcase
{
    private $gitFilePath;
    private $gitLoader;

    protected function setUp()
    {
        GitDirectoryFixtures::createGitDirectories();
        $this->gitFilePath = new GitFilePath(__DIR__);
        $this->gitLoader = new GitLoader($this->gitFilePath);
    }

    public function testGetBranchName()
    {
        $this->assertEquals("master", $this->gitLoader->getBranchName());
    }

    public function testGetLastCommit()
    {
        $this->assertEquals("first commit !", $this->gitLoader->getLastCommitMessage());
    }

    public function testGetLastCommitDetail()
    {
        $lastCommitExpected = [
            'sha' => "c167d99",
            'author' => "eniams",
            'email' => "contact@smaine.me",
            'date' => "2018/07/22 17:37",
            'urlCommit' => "https://github.com/my_account/project.com/commit/c167d99",
        ];

        $this->assertEquals($lastCommitExpected, $this->gitLoader->getLastCommitDetail());
    }

    public function testGetBaseUrlRepository()
    {
        $this->assertEquals("https://github.com/my_account/project.com.git", $this->gitLoader->getBaseUrlRepository());
    }

    protected function tearDown()
    {
        GitDirectoryFixtures::removeGitDirectories();
    }
}
