<?php

namespace App\Mcp\Servers;

use Laravel\Mcp\Server;
use App\Mcp\Tools\GetUserCoursesTool;
use App\Mcp\Tools\GetPaymentStatusTool;
use App\Mcp\Tools\GetCourseInfoTool;
use App\Mcp\Tools\CreateSupportTicketTool;
use App\Mcp\Tools\GenerateInvoiceDataTool;
use App\Mcp\Tools\UpdateStudentIssueStatusTool;
use App\Mcp\Tools\RecommendCourseBasedOnProgressTool;

class SupportServer extends Server
{
    /**
     * The MCP server's name.
     */
    protected string $name = 'Student Support Server';

    /**
     * The MCP server's version.
     */
    protected string $version = '1.0.0';

    /**
     * The MCP server's instructions for the LLM.
     */
    protected string $instructions = <<<'MARKDOWN'
        You are a helpful AI assistant for a course booking platform. Your role is to assist students with:

        1. **Course Information**: Help students find information about courses, check availability, and understand course details.
        2. **Payment Support**: Check payment status, help with payment issues, and provide invoice information.
        3. **Booking Management**: View user's enrolled courses and booking status.
        4. **Technical Support**: Create support tickets for technical issues or payment problems.
        5. **Course Recommendations**: Suggest courses based on student's current enrollments and progress.

        Always be friendly, professional, and helpful. If you cannot resolve an issue directly, create a support ticket for the student.

        When a payment fails, automatically create a support ticket with high priority.
    MARKDOWN;

    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Tool>>
     */
    protected array $tools = [
        GetUserCoursesTool::class,
        GetPaymentStatusTool::class,
        GetCourseInfoTool::class,
        CreateSupportTicketTool::class,
        GenerateInvoiceDataTool::class,
        UpdateStudentIssueStatusTool::class,
        RecommendCourseBasedOnProgressTool::class,
    ];

    /**
     * The resources registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Resource>>
     */
    protected array $resources = [
        //
    ];

    /**
     * The prompts registered with this MCP server.
     *
     * @var array<int, class-string<\Laravel\Mcp\Server\Prompt>>
     */
    protected array $prompts = [
        //
    ];
}
