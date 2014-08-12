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

        if (!$output->isQuiet() && $output->isDebug()) {
            $output->writeln("<info>Initizing Validator</info>");
        }

        $emails = array();

        if (!$output->isQuiet() && $output->isDebug()) {
            $output->writeln("<info>Checking if emails argument is present or not</info>");
        }
        if ($input->getArgument('emails')) {
            if (!$output->isQuiet() && $output->isDebug()) {
                $output->writeln("<info>emails argument found - processing emails</info>");
            }
            $emails = array_merge($emails, $input->getArgument('emails'));
            if (!$output->isQuiet() && $output->isDebug()) {
                $output->writeln("<info>processed emails... ".count($emails)." emails are in the queue</info>");
            }
        }

        if (!$output->isQuiet() && $output->isDebug()) {
            $output->writeln("<info>checking is in-file is specified or not</info>");
        }
        if($input->getOption('in-file')) {
            if (!$output->isQuiet() && $output->isDebug()) {
                $output->writeln("<info>in-file argument found - processing emails</info>");
            }
            $in_file = fopen($input->getOption('in-file'), "r");
            while(! feof($in_file)) {
                array_push($emails, fgetcsv($in_file)[0]);
            }
            if (!$output->isQuiet() && $output->isDebug()) {
                $output->writeln("<info>processed emails... ".count($emails)." emails are in the queue</info>");
            }
            fclose($in_file);
        }

        if (!$output->isQuiet() && $output->isDebug()) {
            $output->writeln("<info>checking if out-file is specified or not</info>");
        }
        if($input->getOption('out-file')) {
            if (!$output->isQuiet() && $output->isDebug()) {
                $output->writeln("<info>user specified out-file found</info>");
            }
            $out_file = fopen($input->getOption('out-file'),"w");
        } else {
            if (!$output->isQuiet() && $output->isDebug()) {
                $output->writeln("<info>creating a default out-file: eyeball.log</info>");
            }
            $out_file = fopen('eyeball.log', 'w');
        }
        
        if (!$output->isQuiet() && $output->isDebug()) {
            $output->writeln("<info>processing emails...</info>");
        }
        $ctr=0;
        foreach ($emails as $email) {
            if (!$output->isQuiet() && $output->isDebug()) {
                $output->writeln("<info>processing email <".$email."> #".++$ctr."...</info>");
            }
            $validator = new ValidateEmail($email, $input->getOption('from-email'));
            try {
                $result = $validator->validate();
                fputcsv($out_file, array($email, $result[$email]));
                if (!$output->isQuiet() && $output->isDebug()) {
                    $output->writeln("<info>email <".$email.">processed...</info>");
                }
            } catch(\Exception $e) {
                fputcsv($out_file, array($email, 'Exception Occured'));
                if (!$output->isQuiet() && $output->isDebug()) {
                    $output->writeln("<info>error occured at #".$ctr.". Making a note of it...</info>");
                }
            }
            unset($validator);
        }

        if(isset($out_file)) fclose($out_file);
        if(isset($in_file)) fclose($in_file);
        
        if (!$output->isQuiet() && $output->isDebug()) {
            $output->writeln("<info>all done. Good Bye!</info>");
        }

    }
}
