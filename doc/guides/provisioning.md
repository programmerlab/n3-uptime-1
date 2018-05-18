# How N3 Uptime handles instance provisioning

(This is a quick and dirty distillation of [Puppet's docs](https://docs.puppet.com/puppet/4.3/reference/), which are surprisingly good!)

When reading this, the main thing to keep in mind is that Puppet is declarative and (more or less) idempotent. With some exceptions (the main one, `require`, I'll mention below), there's no requirement that things run in a certain order: Puppet manifests merely declare "this is the way I want things to look when you're all done, I don't care how or in what order you do it." 

In the manifests, you'll see things like "ensure" which translate to "do this if it isn't already done, and fix the config if it differs from how I declared it here." Because of this, for the most part, there's no need for conditionals to ensure the system is in a particular state before performing a task.
