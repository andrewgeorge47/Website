<?php
class GitHubHandler {
    private $token;
    private $owner;
    private $repo;
    private $path;

    public function __construct($token, $owner, $repo, $path = 'submissions.csv') {
        $this->token = $token;
        $this->owner = $owner;
        $this->repo = $repo;
        $this->path = $path;
    }

    public function appendToFile($data) {
        // First, get the current content and SHA
        $currentContent = $this->getFileContent();
        $sha = $currentContent['sha'] ?? null;
        
        // Prepare the new content
        $newContent = $currentContent['content'] ?? "Timestamp,Name,Email,Role,Message\n";
        $newContent .= implode(',', array_map(function($field) {
            return '"' . str_replace('"', '""', $field) . '"';
        }, $data)) . "\n";
        
        // Encode the content
        $encodedContent = base64_encode($newContent);
        
        // Prepare the API request
        $url = "https://api.github.com/repos/{$this->owner}/{$this->repo}/contents/{$this->path}";
        $data = [
            'message' => 'New form submission',
            'content' => $encodedContent,
            'sha' => $sha
        ];
        
        // Make the API request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: token ' . $this->token,
            'Content-Type: application/json',
            'User-Agent: PHP'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200 || $httpCode === 201;
    }

    private function getFileContent() {
        $url = "https://api.github.com/repos/{$this->owner}/{$this->repo}/contents/{$this->path}";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: token ' . $this->token,
            'User-Agent: PHP'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            return json_decode($response, true);
        }
        
        return null;
    }
}
?> 