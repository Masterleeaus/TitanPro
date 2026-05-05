<?php
namespace Modules\CleaningJobs\Services\AI;
class BookingAgentRuntime { public function __construct(private readonly KnowledgeRetrievalService $retrieval, private readonly AgentToolRegistry $tools, private readonly AgentGuardrailService $guardrails) {} public function respond(array $message, array $context = []): array { return ["agent"=>"cleaning_jobs.booking_agent","sources"=>$this->retrieval->retrieve($message["text"]??"", $context),"available_tools"=>$this->tools->names()]; } }
