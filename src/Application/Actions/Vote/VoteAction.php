<?php

declare(strict_types=1);

namespace App\Application\Actions\Vote;
use App\Models\DBConnection;
use App\Application\Actions\Action;
use Psr\Log\LoggerInterface;
use App\Exception\ValidationException;

abstract class VoteAction extends Action
{
    //protected CommentRepository $commentRepository;
    public function __construct(LoggerInterface $logger,DBConnection $_db)
    {
        parent::__construct($logger,$_db);
        //$this->commentRepository = $commentRepository;
    }
}
