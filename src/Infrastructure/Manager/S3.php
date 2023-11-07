<?php declare(strict_types=1);

namespace App\Infrastructure\Manager;

use App\Domain\Manager\UploadFileManager;
use App\Infrastructure\Exceptions\UploadFileUrlNotValidException;
use Aws\S3\S3Client;
use Exception;

class S3 implements UploadFileManager
{
    private S3Client $s3Client;
    private string $bucket;
    private string $tmpFolder;

    public function __construct(
        S3Client $s3Client,
        string $bucket,
        string $tmpFolder
    )
    {
        $this->s3Client = $s3Client;
        $this->bucket = $bucket;
        $this->tmpFolder = str_ends_with("/", $tmpFolder) ? $tmpFolder : $tmpFolder . "/";

        if (!is_dir($this->tmpFolder)) {
            mkdir(directory: $this->tmpFolder, recursive: true);
        }
    }

    /**
     * @param string $filePath
     * @param string $uploadPath
     * @param string $fileName
     * @param bool $publicRead
     * @return string
     */
    public function uploadFile(string $filePath, string $uploadPath = "", string $fileName = "", bool $publicRead = false): string
    {
        $key = $fileName ?: basename($filePath);
        $uploadPath = rtrim($uploadPath, '/') . '/';

        $acl = $publicRead ? 'public-read' : 'private';

        $result = $this->s3Client->putObject(
            [
                'Bucket' => $this->bucket,
                'Key' => $uploadPath . $key,
                'SourceFile' => $filePath,
                "ACL" => $acl,
                "ContentType" => mime_content_type($filePath)
            ]
        );

        return $result->get("ObjectURL");
    }

    /**
     * @param string $fileUrl
     * @param string $uploadPath
     * @param string $fileName
     * @return string
     * @throws UploadFileUrlNotValidException
     * @throws Exception
     */
    public function uploadFileUrl(string $fileUrl, string $uploadPath = "", string $fileName = "", bool $publicRead = false): string
    {
        if (empty($fileName)) {
            $fileName = basename(strtok($fileUrl, '?'));
        }

        $tmpFile = $this->tmpFolder . uniqid("", true) . $fileName;

        if (!$this->urlIsValid($fileUrl)) {
            throw new UploadFileUrlNotValidException();
        }

        $ch = curl_init($fileUrl);
        $fp = fopen($tmpFile, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        try {
            $result = $this->uploadFile($tmpFile, $uploadPath, $fileName, $publicRead);
            unlink($tmpFile);
        } catch (Exception $e) {
            unlink($tmpFile);
            throw $e;
        }

        return $result;
    }

    /**
     * @param string $fileBase64
     * @param string $uploadPath
     * @param string $fileName
     * @return string
     * @throws Exception
     */
    public function uploadFileBase64(string $fileBase64, string $uploadPath, string $fileName, bool $publicRead = false): string
    {
        $tmpFileNoExtension = $this->tmpFolder . uniqid("", true) . $fileName;

        //Decode content
        $decoded = base64_decode($fileBase64);
        $file = fopen($tmpFileNoExtension, 'w');
        fwrite($file, $decoded);
        fclose($file);

        $extension = explode('/', mime_content_type($tmpFileNoExtension))[1];

        if (empty($fileName) || !str_ends_with($fileName, "." . $extension)) {
            $tmpFile = $tmpFileNoExtension . "." . $extension;
            $fileName .= "." . $extension;
            rename($tmpFileNoExtension, $tmpFile);
        } else {
            $tmpFile = $tmpFileNoExtension;
        }

        //dd($tmpFile);

        try {
            $result = $this->uploadFile($tmpFile, $uploadPath, $fileName, $publicRead);
            unlink($tmpFile);
        } catch (Exception $e) {
            unlink($tmpFile);
            throw $e;
        }

        return $result;
    }

    private function urlIsValid(string $url): bool
    {
        $headers = @get_headers($url);

        return $headers && strpos($headers[0], '200');
    }


    public function getPublicUrl(string $privateUrl, int $expirationInSeconds): string
    {
        //dd(ltrim(string: parse_url(url: $privateUrl)["path"],characters: "/"));
        $cmd = $this->s3Client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => ltrim(string: parse_url(url: $privateUrl)["path"], characters: "/"),
        ]);

        $request = $this->s3Client->createPresignedRequest($cmd, '+' . $expirationInSeconds . ' seconds');

        return (string)$request->getUri();
    }

    public function getFileContent(string $fileUrl)
    {
        $result = $this->s3Client->getObject(
            [
                'Bucket' => $this->bucket,
                'Key' => ltrim(string: parse_url(url: $fileUrl)["path"], characters: "/"),
            ]);

        return $result['Body'];
    }
}