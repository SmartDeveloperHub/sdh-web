# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.define "vdocker" do |app|
  	app.vm.box = "ubuntu/trusty64"
  	config.vm.network :forwarded_port, guest: 80, host: 8080
  	config.vm.network :forwarded_port, guest: 443, host: 4430
    config.vm.provider :virtualbox do |vb|
          vb.name = "LaravelDocker"
          vb.memory = 2048
    end
  	#app.vm.provision "docker" do |d|
	#	d.build_image "/vagrant/docker2"
    #end
  	app.vm.provision "docker", images: ["ubuntu"]
    app.vm.provision "shell", path: "vagrant/exec_once.sh", :privileged => true, run: "once"
    app.vm.provision "shell", path: "vagrant/exec_always.sh", :privileged => true, run: "always"
  end
end