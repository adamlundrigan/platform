<?php

namespace Oro\Bundle\ImapBundle\Command;

use Doctrine\ORM\EntityManager;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Oro\Component\Log\OutputLogger;
use Oro\Bundle\ImapBundle\Manager\ImapClearManager;

class ClearInactiveMailboxCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var OutputLogger
     */
    protected $logger;

    /**
     * {@internaldoc}
     */
    protected function configure()
    {
        $this->setName('oro:imap:clear-mailbox')
            ->setDescription('Clears inactive mailboxes')
            ->addOption(
                'id',
                null,
                InputOption::VALUE_OPTIONAL,
                'Id of origin to clear'
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ImapClearManager $cleaner */
        $cleaner = $this->getContainer()->get('oro_imap.manager.clear');
        $this->logger = new OutputLogger($output);

        $this->em = $this->getContainer()->get('doctrine')->getManager();

        $originId = $input->getOption('id');

        if (!$cleaner->clear($originId)) {
            $this->logger->notice('Nothing to clear');

            return;
        }

        $this->logger->notice('Finished');
    }
}
