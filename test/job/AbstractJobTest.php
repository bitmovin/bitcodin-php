<?php

    namespace test\job;

    require_once __DIR__ . '/../../vendor/autoload.php';

    use bitcodin\Job;
    use bitcodin\Output;
    use bitcodin\S3OutputConfig;
    use DateTime;
    use test\BitcodinApiTestBaseClass;

    abstract class AbstractJobTest extends BitcodinApiTestBaseClass
    {

        public function setUp()
        {
            parent::setUp();
        }

        /** HELPER METHODS **/
        protected function updateJob(Job $job)
        {
            /* WAIT TIL JOB IS FINISHED */
            $this->waitTillJobGetsExpectedStatus($job, Job::STATUS_FINISHED, Job::STATUS_ERROR);
            $this->assertEquals($job->status, Job::STATUS_FINISHED);

            return $job;
        }

        protected function updateJobError(Job $job)
        {
            $this->waitTillJobGetsExpectedStatus($job, Job::STATUS_ERROR, Job::STATUS_FINISHED);
            $this->assertEquals($job->status, Job::STATUS_ERROR);

            return $job;
        }

        private function waitTillJobGetsExpectedStatus(Job $job, $expectedStatus, $notExpectedStatus, $timeOutSeconds = 1000)
        {
            $expireTime = (new DateTime())->add(new \DateInterval('PT' . $timeOutSeconds . 'S'));
            do {
                sleep(2);
                $job->update();
                $this->assertNotEquals($job->status, $notExpectedStatus);
                $this->assertTrue($expireTime >= new DateTime(), 'Timeout during job update!');

            } while ($job->status != $expectedStatus);
        }

        protected function transferJob(Job $job)
        {
            $s3Config = $this->getKey('s3output');
            $outputConfig = new S3OutputConfig();
            $outputConfig->accessKey = $s3Config->accessKey;
            $outputConfig->secretKey = $s3Config->secretKey;
            $outputConfig->name = $s3Config->name;
            $outputConfig->bucket = $s3Config->bucket;
            $outputConfig->region = $s3Config->region;
            $outputConfig->makePublic = false;

            $output = Output::create($outputConfig);
            /* WAIT TIL JOB IS FINISHED */
            $job->transfer($output);
        }
    }
