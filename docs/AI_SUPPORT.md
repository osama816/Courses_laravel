# AI Support System Documentation

## Overview

This document describes the AI Support System integrated into the Laravel course booking platform. The system uses Laravel MCP (Model Context Protocol) to provide intelligent AI-powered support to students through a chat interface.

## Features

### 1. AI Support Chat Widget
- **Location**: Floating button on all authenticated pages
- **Component**: `<x-ai-support />` Blade component
- **Features**:
  - Real-time chat interface
  - Conversation history (stored in localStorage)
  - Quick action buttons
  - Responsive design
  - Typing indicators

### 2. MCP Tools

The system includes the following MCP tools for AI assistance:

#### GetUserCoursesTool
- **Purpose**: Retrieve all courses a user has booked
- **Parameters**: `user_id` (required)
- **Returns**: List of courses with details (title, price, status, category, instructor)

#### GetPaymentStatusTool
- **Purpose**: Check payment status for a booking or payment
- **Parameters**: `payment_id` or `booking_id` (one required)
- **Returns**: Payment details including amount, status, transaction ID, and invoice information

#### GetCourseInfoTool
- **Purpose**: Get detailed information about a specific course
- **Parameters**: `course_id` (required)
- **Returns**: Course details including title, description, price, instructor, availability

#### CreateSupportTicketTool
- **Purpose**: Create a support ticket for user issues
- **Parameters**: 
  - `user_id` (required)
  - `subject` (required)
  - `message` (required)
  - `priority` (optional: low, medium, high, urgent)
- **Returns**: Ticket number and status

#### GenerateInvoiceDataTool
- **Purpose**: Generate invoice data for a payment
- **Parameters**: `payment_id` or `invoice_id` (one required)
- **Returns**: Invoice details including invoice number, amount, payment details, and download URL

#### UpdateStudentIssueStatusTool
- **Purpose**: Update the status of a support ticket
- **Parameters**: 
  - `ticket_id` (required)
  - `status` (required: open, in_progress, resolved, closed)
- **Returns**: Updated ticket information

#### RecommendCourseBasedOnProgressTool
- **Purpose**: Recommend courses based on user's current enrollments
- **Parameters**: `user_id` (required)
- **Returns**: List of recommended courses based on category and level

## Installation & Setup

### 1. Dependencies

The following packages are required:
- `laravel/mcp`: ^0.3.4 (already installed)
- `barryvdh/laravel-dompdf`: For PDF invoice generation

### 2. Database Migrations

Run the following migrations:
```bash
php artisan migrate
```

This will create:
- `invoices` table
- `support_tickets` table

### 3. Configuration

The MCP server is configured in `routes/ai.php`:
```php
Mcp::web('/support', SupportServer::class);
```

The web endpoint is available at: `/mcp/support`

### 4. Routes

The following routes are available:
- `POST /mcp/support/chat` - Chat endpoint (authenticated)
- `GET /invoice/{id}/download` - Download invoice PDF (authenticated)
- `GET /invoice/{id}` - View invoice (authenticated)

## Usage

### For Students

1. **Accessing AI Support**:
   - Look for the floating chat button in the bottom-right corner
   - Click to open the chat widget
   - Start typing your question

2. **Common Queries**:
   - "What courses do I have?"
   - "Check my payment status"
   - "Show my invoices"
   - "I need help with payment"
   - "Recommend courses for me"

3. **Quick Actions**:
   - Use the quick action buttons for common tasks
   - View conversation history (stored locally)

### For Developers

#### Adding New MCP Tools

1. Create a new tool class in `app/Mcp/Tools/`:
```php
<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;

class YourNewTool extends Tool
{
    protected string $description = 'Tool description';
    
    public function handle(Request $request): Response
    {
        // Your logic here
        return Response::text('Response');
    }
    
    public function schema(JsonSchema $schema): array
    {
        return [
            'param_name' => $schema->string()
                ->title('Param Title')
                ->description('Param description')
                ->required(),
        ];
    }
}
```

2. Register the tool in `app/Mcp/Servers/SupportServer.php`:
```php
protected array $tools = [
    // ... existing tools
    YourNewTool::class,
];
```

#### Customizing the Chat Widget

The chat widget component is located at:
`resources/views/components/ai-support.blade.php`

You can customize:
- Colors and styling
- Quick action buttons
- Welcome message
- Response formatting

## Invoice System

### Automatic Invoice Generation

Invoices are automatically created when:
- A payment is successfully processed via MyFatoorah
- Payment status is "Paid"

### Invoice Features

- **Unique Invoice Numbers**: Format: `INV-YYYYMMDD-####`
- **PDF Generation**: Automatic PDF creation using DomPDF
- **Storage**: PDFs stored in `storage/app/invoices/`
- **Download**: Available via `/invoice/{id}/download`

### Invoice Model

```php
Invoice::create([
    'invoice_number' => Invoice::generateInvoiceNumber(),
    'user_id' => $userId,
    'payment_id' => $paymentId,
    'booking_id' => $bookingId,
    'amount' => $amount,
    'status' => 'paid',
    'issued_at' => now(),
]);
```

## Support Ticket System

### Automatic Ticket Creation

Support tickets are automatically created when:
- A payment fails
- User requests help through AI chat

### Ticket Features

- **Unique Ticket Numbers**: Format: `TKT-YYYYMMDD-####`
- **Priority Levels**: low, medium, high, urgent
- **Status Tracking**: open, in_progress, resolved, closed
- **Auto-resolution**: Tickets marked as resolved when status changes

## Integration with MyFatoorah

The system is fully integrated with MyFatoorah payment gateway:

1. **Payment Processing**:
   - Payment initiated through `PaymentController::paymentProcess()`
   - Redirects to MyFatoorah payment page

2. **Payment Callback**:
   - `PaymentController::callBack()` handles the response
   - On success: Creates payment record and invoice
   - On failure: Creates support ticket with high priority

3. **Invoice Creation**:
   - Automatically generated after successful payment
   - Linked to payment, booking, and user
   - PDF generated on first download

## Testing

### Manual Testing Steps

1. **Test AI Support Chat**:
   ```bash
   # Login as a user
   # Click the AI support button
   # Try various queries
   ```

2. **Test Payment Flow**:
   ```bash
   # Create a booking
   # Process payment via MyFatoorah
   # Verify invoice creation
   # Download invoice
   ```

3. **Test MCP Tools**:
   ```bash
   # Use AI chat to test each tool
   # Verify responses are accurate
   ```

### Unit Tests

Create tests in `tests/Feature/`:
- `McpSupportTest.php`
- `InvoiceTest.php`
- `SupportTicketTest.php`

## Troubleshooting

### Common Issues

1. **AI Chat Not Responding**:
   - Check `/mcp/support/chat` route is accessible
   - Verify authentication middleware
   - Check browser console for errors

2. **Invoice Not Generated**:
   - Verify payment status is "Paid"
   - Check database for invoice record
   - Review payment callback logs

3. **PDF Generation Fails**:
   - Ensure DomPDF is installed: `composer require barryvdh/laravel-dompdf`
   - Check storage permissions
   - Verify invoice data is complete

### Logs

Check the following log files:
- `storage/logs/laravel.log` - General application logs
- `storage/logs/payment.log` - Payment-related logs

## Security Considerations

1. **Authentication**: All routes require authentication
2. **Authorization**: Users can only access their own data
3. **CSRF Protection**: All POST requests include CSRF tokens
4. **Input Validation**: All user inputs are validated

## Future Enhancements

- [ ] Integration with external AI providers (OpenAI, Anthropic)
- [ ] Multi-language support for AI responses
- [ ] Email notifications for support tickets
- [ ] Admin dashboard for ticket management
- [ ] Advanced analytics and reporting
- [ ] Voice input support
- [ ] Mobile app integration

## Support

For issues or questions:
- Check the logs: `storage/logs/laravel.log`
- Review this documentation
- Contact the development team

## Version History

- **v1.0.0** (2025-11-30): Initial implementation
  - AI Support Chat Widget
  - MCP Tools integration
  - Invoice system
  - Support ticket system
  - MyFatoorah integration

