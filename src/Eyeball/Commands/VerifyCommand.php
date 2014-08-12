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
                'from-email',null,
                InputOption::VALUE_REQUIRED,
                'Specify a from email'
            )
            ->addOption(
               'in-file',
               null,
               InputOption::VALUE_OPTIONAL,
               'Scan email addresses from a CSV file with one email per line.'
            )
            ->addOption(
               'out-file',
               null,
               InputOption::VALUE_REQUIRED,
               'Specify a file to write output to.'
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

        if($input->getOption('in-file')) {
            $in_file = fopen($input->getOption('in-file'), "r");
            while(! feof($in_file)) {
                array_push($emails, fgetcsv($in_file)[0]);
            }
            fclose($in_file);
        }

        if($input->getOption('out-file')) {
            $out_file = fopen($input->getOption('out-file'),"w");
        } else {
            $out_file = fopen('eyeball.log', 'w');
        }
        
        $ctr=0;
        foreach ($emails as $email) {
            $validator = new ValidateEmail($email, $input->getOption('from-email'));
            try {
                $result = $validator->validate();
                fputcsv($out_file, array($email, $result[$email]));
            } catch(\Exception $e) {
                fputcsv($out_file, array($email, $e->getMessage()));
            }
            unset($validator);
        }

        if(isset($out_file)) fclose($out_file);
    }
}
