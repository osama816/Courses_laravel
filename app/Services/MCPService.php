<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;

class MCPService
{
    protected $serverProcess;
    protected $serverPath;

    public function __construct()
    {
        $this->serverPath = base_path('mcp-server');
    }

    public function start()
    {
        // تشغيل MCP server كـ background process
        $this->serverProcess = Process::start([
            'node',
            $this->serverPath . '/index.js'
        ]);

        // انتظار بدء السيرفر
        sleep(2);

        return $this->serverProcess->running();
    }

    public function callTool($toolName, $arguments = [])
    {
        // استدعاء tool من خلال stdio
        $request = json_encode([
            'method' => 'tools/call',
            'params' => [
                'name' => $toolName,
                'arguments' => $arguments
            ]
        ]);

        // إرسال الطلب للـ MCP server
        $response = $this->sendRequest($request);

        return json_decode($response, true);
    }

    protected function sendRequest($request)
    {
        // التواصل مع MCP server عبر stdio
        $process = Process::run([
            'node',
            $this->serverPath . '/client.js',
            $request
        ]);

        return $process->output();
    }

    public function stop()
    {
        if ($this->serverProcess) {
            $this->serverProcess->stop();
        }
    }
}
