<?php


namespace Eniams\Bundle\GitProfilerBundle\BranchLoader;

/**
 * Responsible to get information from git directory
 *
 * @author Smaine Milianni <contact@smaine.me>
 */
class GitLoader
{
    const NO_BRANCH = 'There is no branch';
    const NO_COMMIT = 'There is no commit';
    const NOT_DEFINED = 'not defined';
    const NO_REMOTE_REPO = 'There is no remote repository';

    private $commitEditMessage;
    private $configFile;
    private $gitLogFile;
    private $headFile;

    /**
     * @param GitFilePath $filePath
     */
    public function __construct(GitFilePath $filePath)
    {
        $this->headFile = $filePath->getHeadPathFile();
        $this->configFile = $filePath->getConfigPathFile();
        $this->commitEditMessage = $filePath->getCommitEditMessagePathFile();
        $this->gitLogFile = $filePath->getGitLogPathFile();
    }

    /**
     * Get the current branch name
     *
     * @return string Current branch name or an empty string if
     *                the branch name is undefined
     */
    public function getBranchName(): string
    {
        $branchname = self::NO_BRANCH;
        $stringFromFile = file_exists($this->headFile) ? file($this->headFile, FILE_USE_INCLUDE_PATH) : "";

        if (isset($stringFromFile) && is_array($stringFromFile)) {
            //get the string from the array
            $firstLine = $stringFromFile[0];
            //seperate out by the "/" in the string
            $explodedString = explode("/", $firstLine, 3);

            $branchname = trim($explodedString[2]);
        }

        return $branchname;
    }

    /**
     * Get the last commit message
     *
     * @return string Last commit message or an empty string if the
     *                the last commit message is undefined
     */
    public function getLastCommitMessage(): string
    {
        if (!file_exists($this->commitEditMessage)) {
            return self::NO_COMMIT;
        }

        return trim(file($this->commitEditMessage, FILE_USE_INCLUDE_PATH)[0]);
    }

    /**
     * Get the details of the last commit
     *
     * @return array Details of the last commit or an empty array
     *               if the details of the last commit are undefined
     *
     * @throws InvalidUrlException If there is no correct url
     */
    public function getLastCommitDetail(): array
    {
        $logs = $this->getLogs();

        return \is_array($logs) ? end($logs) : [];
    }

    /**
     * Get the details of the last commit or null if there is no logs
     *
     * @return null|array
     *
     * @throws InvalidUrlException If there is no correct url
     */
    public function getLogs(): ?array
    {
        $logs = [];
        $noRepo = true;
        $gitLogs = file_exists($this->gitLogFile) ? file($this->gitLogFile, FILE_USE_INCLUDE_PATH) : "";

        if (is_string($gitLogs)) {
            return null;
        }

        if (self::NO_REMOTE_REPO !== $this->getBaseUrlRepository()) {
            $noRepo = false;
            $baseRepository = str_replace(".git", '', $this->getBaseUrlRepository());
        }

        foreach ($gitLogs as $item => $value) {
            $logExploded = explode(' ', $value);
            $logs[$item]['sha'] = $logExploded[1] ?? self::NOT_DEFINED;
            $logs[$item]['author'] = $logExploded[2] ?? self::NOT_DEFINED;
            $logs[$item]['email'] = preg_replace('#<|>#', '', $logExploded[3]) ?? self::NOT_DEFINED;
            $logs[$item]['date'] = isset($logExploded[4]) ? date('Y/m/d H:i', $logExploded[4]) : self::NOT_DEFINED;
            $logs[$item]['urlCommit'] = !$noRepo ? $baseRepository . "/commit/" . $logs[$item]['sha'] : self::NO_REMOTE_REPO;
        }

        return $logs;
    }

    /**
     * Get the repository url
     *
     * @return string The repository Url
     *
     * @throws InvalidUrlException If there is no correct url
     */
    public function getBaseUrlRepository(): string
    {
        $fileContent = file_exists($this->configFile) ? file($this->configFile, FILE_USE_INCLUDE_PATH) : self::NO_REMOTE_REPO;

        if (!\is_array($fileContent)) {
            return $fileContent;
        }

        if (!isset($fileContent[6])) {
            return self::NO_REMOTE_REPO;
        }

        $url = trim($fileContent[6]);

        if (false !== $git = strpos($url, "https")) {
            return str_replace('url = ', '', $url);
        }

        if (false !== strpos($url, "git@")) {
            $gitDomain = substr($url, 10, 10);
            return str_replace("url = git@$gitDomain:", "https://$gitDomain/", $url);
        }

        throw new InvalidUrlException(sprintf("the value %s is invalid", $url));
    }
}
