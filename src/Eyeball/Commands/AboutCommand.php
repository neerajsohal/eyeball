<?php

namespace Eyeball\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Finder\Finder;

class AboutCommand extends Command {

    protected function configure() {
        $this->setName('about')
            ->setDescription('Short information about Eyeball');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln(<<<EOT
<info>Eyeball - Verify Email Addresses</info>
<comment>Eyeball is a email address verifier. It uses SMTP for verifying email addresses provided as an option list or in a csv file.</comment>
EOT
        );
    }
}