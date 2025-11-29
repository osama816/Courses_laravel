<?php

use Laravel\Mcp\Facades\Mcp;
use App\Mcp\Servers\SupportServer;

// Mcp::web('/mcp/demo', \App\Mcp\Servers\PublicServer::class);

Mcp::web('/support',SupportServer::class);
