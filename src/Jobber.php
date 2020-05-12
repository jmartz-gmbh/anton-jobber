<?php
namespace Anton;

class Jobber {

    public function run(){
        $filename = 'storage/jobber.json';
        if(!file_exists($filename)){
            file_put_contents($filename, '[]');
        }

        $jobs = $this->getJobs();

        if(count($jobs) > 0){
            foreach ($jobs as $key => $value) {
                echo 'run: '.$value['name']. ' - '. $value['pipeline'].PHP_EOL;
                $this->executeJob($value['name'], $value['pipeline']);
            }

            file_put_contents($filename, '[]');
        }
        else{
            echo 'No jobs found. '.PHP_EOL;
        }
    }

    public function executeJob(string $name, string $pipeline){
        if(empty($name) || empty($pipeline)){
            throw new \Exception('Project Name or Pipeline empty');
        }

        exec('./anton.sh trigger '.$name.' '.$pipeline);
    }

    public function getJobs(){
        $filename = 'storage/jobber.json';

        if(file_exists($filename)){
            $json = file_get_contents($filename);
            $data = json_decode($json, true);
            if(!empty($data)){
                return $data;
            }
        }
        else{
            \file_put_contents($filename, '{}');
        }

        return [];
    }

}