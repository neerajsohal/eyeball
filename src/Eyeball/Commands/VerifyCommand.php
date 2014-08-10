<?php

namespace Eyeball\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class VerifyCommand extends Command {

    protected function configure() {   
        $this
            ->setName('verify:emails')
            ->setDescription('Verify Given Email Addresses')
            ->addArgument(
                'file',
                InputArgument::OPTIONAL,
                'Scan email addresses from a CSV file with one email per line.'
            )
            ->addArgument(
                'emails',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Emails you want to verify (separate multiple emails with a space)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {


    }
}
