buildscript {
    repositories {
		jcenter()
		maven { url "http://files.minecraftforge.net/maven" }
		maven {
			url "https://plugins.gradle.org/m2/"
		}
    }
    dependencies {
        classpath 'net.minecraftforge.gradle:ForgeGradle:2.3-SNAPSHOT'
    }
}
apply plugin: 'net.minecraftforge.gradle.forge'
// Only edit below this line, the above code adds and enables the necessary things for Forge to be setup.
apply plugin: 'java'

version = "0.0.4"
group = "io.github.lukas2005.lda" // http://maven.apache.org/guides/mini/guide-naming-conventions.html
archivesBaseName = "lda"

def jxbrowser_version = "6.16"

compileJava.options.encoding = 'UTF-8'
sourceCompatibility = targetCompatibility = '1.8' // Need this here so eclipse task generates correctly.
compileJava {
    sourceCompatibility = targetCompatibility = '1.8'
}

repositories {
	maven { url 'https://jitpack.io' }
	maven { url 'http://repo.jenkins-ci.org/releases' }
}

minecraft {
    version = "1.12.2-14.23.1.2555"
    runDir = "run"
    
    // the mappings can be changed at any time, and must be in the following format.
    // snapshot_YYYYMMDD   snapshot are built nightly.
    // stable_#            stables are built at the discretion of the MCP team.
    // Use non-default mappings at your own risk. they may not always work.
    // simply re-run your setup task after changing the mappings to update your workspace.
    mappings = "snapshot_20171003"
    // makeObfSourceJar = false // an Srg named sources jar is made by default. uncomment this to disable.
}

// Add "embedded" configuration
configurations {
    embed
    compile.extendsFrom(embed)
}

dependencies {
    // you may put jars on which you depend on in ./libs
    // or you may define them like so..
    //compile "some.group:artifact:version:classifier"
    //compile "some.group:artifact:version"
      
	embed ('com.google.apis:google-api-services-youtube:v3-rev186-1.23.0') {
		exclude group: 'com.google.guava'
	}

	embed files("lib/vorbisspi1.0.3.jar")
	embed files("lib/tritonus_share.jar")
	embed files("lib/jorbis-0.0.15.jar")
	embed files("lib/jogg-0.0.7.jar")
	
	embed files("lib/jxbrowser/licence.jar")
	embed files("lib/jxbrowser/lib/jxbrowser-"+jxbrowser_version+".jar")

	compile files("lib/jxbrowser/lib/jxbrowser-linux64-"+jxbrowser_version+".jar")
	compile files("lib/jxbrowser/lib/jxbrowser-mac-"+jxbrowser_version+".jar")
	compile files("lib/jxbrowser/lib/jxbrowser-win32-"+jxbrowser_version+".jar")
	
	embed files("lib/javassist.jar")

	deobfCompile 'com.github.MrCrayfish:MrCrayfishDeviceMod:c2a4b9b85a'

	// https://mvnrepository.com/artifact/org.apache.openjpa/openjpa
	compile group: 'org.apache.openjpa', name: 'openjpa', version: '2.4.2'
	// https://mvnrepository.com/artifact/org.kohsuke/github-api
	compile (group: 'org.kohsuke', name: 'github-api', version: '1.90') {
		exclude group: 'commons-io'
	}


	// real examples
    //compile 'com.mod-buildcraft:buildcraft:6.0.8:dev'  // adds buildcraft to the dev env
    //compile 'com.googlecode.efficient-java-matrix-library:ejml:0.24' // adds ejml to the dev env

    // the 'provided' configuration is for optional dependencies that exist at compile-time but might not at runtime.
    //provided 'com.mod-buildcraft:buildcraft:6.0.8:dev'

    // the deobf configurations:  'deobfCompile' and 'deobfProvided' are the same as the normal compile and provided,
    // except that these dependencies get remapped to your current MCP mappings
    //deobfCompile 'com.mod-buildcraft:buildcraft:6.0.8:dev'
    //deobfProvided 'com.mod-buildcraft:buildcraft:6.0.8:dev'

    // for more info...
    // http://www.gradle.org/docs/current/userguide/artifact_dependencies_tutorial.html
    // http://www.gradle.org/docs/current/userguide/dependency_management.html

}

// Add dependencies to the JAR
jar {
    from configurations.embed.collect {
		exclude '.classpath'
		exclude '.project'
		exclude 'META-INF/LICENSE.txt'
		exclude 'META-INF/NOTICE.txt'
		exclude 'META-INF/LICENSE'
		exclude 'META-INF/NOTICE'
		it.isDirectory() ? it : zipTree(it)
	}
}

processResources {
    // this will ensure that this task is redone when the versions change.
    inputs.property "version", project.version
    inputs.property "mcversion", project.minecraft.version

    // replace stuff in mcmod.info, nothing else
    from(sourceSets.main.resources.srcDirs) {
        include 'mcmod.info'

        // replace version and mcversion
        expand 'version':project.version, 'mcversion':project.minecraft.version
    }
        
    // copy everything else except the mcmod.info
    from(sourceSets.main.resources.srcDirs) {
        exclude 'mcmod.info'
    }
}
//reobfJar.dependsOn shadowJar

// Custom tasks

task makeTmpWin32(type:Exec) {
	commandLine = ['cmd.exe', '/c', 'mkdir', 'tmp']
}

task unpackModWin32(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-xvf', '..\\build\\libs\\'+archivesBaseName+'-'+version+'.jar']
}

task makeTmpMac(type:Exec) {
	commandLine = ['cmd.exe', '/c', 'mkdir', 'tmp']
}

task unpackModMac(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-xvf', '..\\build\\libs\\'+archivesBaseName+'-'+version+'.jar']
}

task makeTmpLinux64(type:Exec) {
	commandLine = ['cmd.exe', '/c', 'mkdir', 'tmp']
}

task unpackModLinux64(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-xvf', '..\\build\\libs\\'+archivesBaseName+'-'+version+'.jar']
}

task unpackLibWin32(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-xvf', '..\\lib\\jxbrowser\\lib\\jxbrowser-win32-'+jxbrowser_version+'.jar']
}

task unpackLibMac(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-xvf', '..\\lib\\jxbrowser\\lib\\jxbrowser-mac-'+jxbrowser_version+'.jar']
}

task unpackLibLinux64(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-xvf', '..\\lib\\jxbrowser\\lib\\jxbrowser-linux64-'+jxbrowser_version+'.jar']
}

task repackWin32(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-cvf',  '..\\build\\'+archivesBaseName+'-'+version+'-win32.jar', '.']
}

task repackMac(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-cvf',  '..\\build\\'+archivesBaseName+'-'+version+'-mac.jar', '.']
}

task repackLinux64(type:Exec) {
	workingDir = 'tmp'
	commandLine = ['cmd.exe', '/c', 'jar', '-cvf',  '..\\build\\'+archivesBaseName+'-'+version+'-linux64.jar', '.']
}

task removeTmpWin32(type:Exec) {
	commandLine = ['cmd.exe', '/c', 'rmdir', '/S', '/Q','tmp']
}

task removeTmpMac(type:Exec) {
	commandLine = ['cmd.exe', '/c', 'rmdir', '/S', '/Q','tmp']
}

task removeTmpLinux64(type:Exec) {
	commandLine = ['cmd.exe', '/c', 'rmdir', '/S', '/Q','tmp']
}

task packageWin32() {
	dependsOn makeTmpWin32
	dependsOn unpackModWin32
	dependsOn unpackLibWin32
	dependsOn repackWin32
	dependsOn removeTmpWin32
	
	tasks.findByName('unpackModWin32').mustRunAfter 'makeTmpWin32'
	tasks.findByName('unpackLibWin32').mustRunAfter 'unpackModWin32'
	tasks.findByName('repackWin32').mustRunAfter 'unpackLibWin32'
	tasks.findByName('removeTmpWin32').mustRunAfter 'repackWin32'
}

task packageMac() {
	dependsOn makeTmpMac
	dependsOn unpackModMac
	dependsOn unpackLibMac
	dependsOn repackMac
	dependsOn removeTmpMac
	
	tasks.findByName('unpackModMac').mustRunAfter 'makeTmpMac'
	tasks.findByName('unpackLibMac').mustRunAfter 'unpackModMac'
	tasks.findByName('repackMac').mustRunAfter 'unpackLibMac'
	tasks.findByName('removeTmpMac').mustRunAfter 'repackMac'
}

task packageLinux64() {
	dependsOn makeTmpLinux64
	dependsOn unpackModLinux64
	dependsOn unpackLibLinux64
	dependsOn repackLinux64
	dependsOn removeTmpLinux64
	
	tasks.findByName('unpackModLinux64').mustRunAfter 'makeTmpLinux64'
	tasks.findByName('unpackLibLinux64').mustRunAfter 'unpackModLinux64'
	tasks.findByName('repackLinux64').mustRunAfter 'unpackLibLinux64'
	tasks.findByName('removeTmpLinux64').mustRunAfter 'repackLinux64'
}

task buildAll() {
	dependsOn build
	dependsOn packageWin32
	dependsOn packageMac
	dependsOn packageLinux64
	
	tasks.findByName('packageWin32').mustRunAfter 'build'
	tasks.findByName('packageMac').mustRunAfter 'packageWin32'
	tasks.findByName('packageLinux64').mustRunAfter 'packageMac'
}