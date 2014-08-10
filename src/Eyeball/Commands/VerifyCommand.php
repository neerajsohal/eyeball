<?php

namespace Eyeball\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Finder\Finder;


use SMTP\ValidateEmail;

class VerifyCommand extends Command {

    protected function configure() {   
        $this
            ->setName('verify:emails')
            ->setDescription('Verify Given Email Addresses')
             ->addOption(
               'file',
               null,
               InputOption::VALUE_OPTIONAL,
               'Scan email addresses from a CSV file with one email per line.'
            )
            ->addArgument(
                'emails',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Emails you want to verify (separate multiple emails with a space)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $emails = array();

        if ($input->getArgument('emails')) {
            $emails = array_merge($emails, $input->getArgument('emails'));
        }

        if($file = $input->getOption('file')) {
            $finder = new Finder();
        }
        
         $validator = new ValidateEmail();
        print_r($validator->validate($emails));
        
    

        // $output->writeln($file);
        // $output->writeln($input->getArgument('emails'));

    }
}
