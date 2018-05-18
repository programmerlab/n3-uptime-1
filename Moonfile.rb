#
# Moonshot configuration
#

$stdout.sync = true
$stderr.sync = true

Moonshot.config do |m|
  m.app_name = 'n3-uptime'
  m.artifact_repository = S3Bucket.new('n3uptime-bucket')
  m.build_mechanism = Script.new('bin/stack/build.sh', output_file: 'dist/output.tar.gz')
  m.deployment_mechanism = CodeDeploy.new(asg: 'AutoScalingGroup')
end
