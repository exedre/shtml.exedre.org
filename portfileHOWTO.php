<?

//
// File     : portfileHOWTO.php
// Version  : $Id: portfileHOWTO.php,v 1.9 2002/12/06 07:15:40 michaelm Exp $
// Location : /projects/darwinports/portfileHOWTO.php
//

	include_once("$DOCUMENT_ROOT/includes/od_lib.inc.php");
	od_print_header("How to Write a DarwinPorts Portfile", "en", "iso-8859-1", "", 0);
?>

<h2>
How to Write a DarwinPorts Portfile
</h2>
<pre><tt>
Kevin Van Vechten | <a href="mailto:kevin@opendarwin.org">kevin@opendarwin.org</a>
8-Oct-2002
</tt></pre>
<h3>
Abstract
</h3>
<p>
DarwinPorts automates the common tasks needed to port software to Darwin.  Portfiles provide the information necessary for building and installing a particular software title correctly on Darwin.  The goal of DarwinPorts is to keep Portfile syntax as simple as possible, while still supporting all the special cases that many software titles require to build and install successfully.
</p>
<p>
This article describes the construction of a simple Portfile, and explores a few of DarwinPorts' most common features.
</p>
<h3>
Getting Started
</h3>
<p>
In order to work with DarwinPorts, you'll need to download and install it on your system.  The DarwinPorts project <a href="http://opendarwin.org/projects/darwinports/">homepage</a> describes how to get and install it.
</p>
<p>
Since you're interested in writing a Portfile, let's change some configuration options that will help in debugging as we go.  Edit the file <tt>/etc/ports/ports.conf</tt> to contain the following (you'll likely have to use <tt>sudo</tt> to edit this file):
</p>
<pre><tt>
ports_debug     yes
ports_verbose   yes
</tt></pre>
<p>
This will display useful debugging messages that are usually suppressed while running DarwinPorts.
</p>
<p>
DarwinPorts performs several basic predefined tasks, these are:
</p>
<a name="basictoc"></a><h4>Basic Topics</h4>
<ul>
<li><a href="#fetch">Fetching the Sources</a></li>
<li><a href="#checksum">Verifying the Downloaded File</a></li>
<li><a href="#extract">Extracting the Sources into a Working Directory</a></li>
<li><a href="#configure">Running a Configure Script</a></li>
<li><a href="#build">Building the Sources</a></li>
<li><a href="#install">Installing the Finished Product on the System</a></li>
</ul>
<a name="advancedtoc"></a><h4>Advanced Topics</h4>
<ul>
<li><a href="#targets">Overriding Targets</a></li>
<li><a href="#variants">Portfile Variants</a></li>
</ul>
<a name="appendixtoc"></a><h4>Appendix</h4>
<ul>
<li><a href="#portfilelist">Portfile Listing</a></li>
<li><a href="#contentslist">Contents Listing</a></li>
</ul>

<h3>
<a name="fetch"></a>Fetching the Sources
</h3>
<p>
The first step is to choose a piece of software to bring to port.  For this example, we'll be porting ircII, a popular internet relay chat client.  We can start with a simple Portfile describing the basic attributes of ircII such as its name, version, and the site where we can download the sources.  Create a working directory named <tt>ircii</tt> and inside it create a file named <tt>Portfile</tt> with the following contents:
</p>
<pre><tt>
PortSystem 1.0
name            ircii
version         20020912
categories      irc
maintainers     kevin@opendarwin.org
master_sites    ftp://ircftp.au.eterna.com.au/pub/ircII/
</tt></pre>
<p>
A Portfile consists of key/value pairs.  The <tt>name</tt> and <tt>version</tt> key describe the name and version of the software.  The <tt>categories</tt> key is a list of the logical categories to which the software belongs; this is used for organizational purposes.  The first entry in <tt>categories</tt> should match the directory in which the port's directory resides in the port tree.  The <tt>maintainers</tt> key should contain your email address, and the <tt>master_sites</tt> key should contain a list of sites where the distribution sources may be downloaded.  DarwinPorts uses the terms 'keys' and 'options' interchangeably since most keys are used as options of a particular task in the porting process.
</p>
<p>
At this point, the Portfile is complete enough to download ircII.  By default, DarwinPorts will append the <tt>version</tt> to the <tt>name</tt> and assume sources are in <tt>.tar.gz</tt> format.  From your working directory, execute the following command:
</p>
<pre><tt>
% port checksum
</tt></pre>
<p>
The <tt>port</tt> command operates on the <tt>Portfile</tt> in the current working directory.  You should see the following output:
</p>
<!--
.........|.........|.........|.........|.........|.........|.........|.........|
-->
<pre><tt>
DEBUG: Executing com.apple.main (ircii)
DEBUG: Executing com.apple.fetch (ircii)
--->  ircii-20020912.tar.gz doesn't seem to exist in /opt/local/var/db/dports/
distfiles
--->  Attempting to fetch ircii-20020912.tar.gz from ftp://
ircftp.au.eterna.com.au/pub/ircII/
DEBUG: Executing com.apple.checksum (ircii)
Error: No checksums statement in Portfile.  File checksums are:
ircii-20020912.tar.gz md5 2ae68c015698f58763a113e9bc6852cc
Error: Target error: com.apple.checksum returned: No checksums statement in 
Portfile.
</tt></pre>

<h3>
<a name="checksum"></a>Verifying the Downloaded File
</h3>
<p>
Notice that DarwinPorts first checks for a local copy of <tt>ircii-20020912.tar.gz</tt> and doesn't find it, so it then downloads from the remote site.  The port doesn't finish because of an error:  "No checksums statement in Portfile."  Portfiles must contain an md5 checksum of all distribution files--this allows DarwinPorts to verify the accuracy and authenticity of the sources.  For convenience, an md5 checksum of the downladed files is printed when the <tt>checksums</tt> argument is not specified.  Go back and add the following to your Portfile:
</p>
<pre><tt>
checksums       md5 2ae68c015698f58763a113e9bc6852cc
</tt></pre>

<h3>
<a name="extract"></a>Extracting the Sources into a Working Directory
</h3>
<p>
Now that we have a checksum and can verify our sources, we can proceed to extracting the sources into our working directory.  Execute the following:
</p>
<pre><tt>
% port extract
</tt></pre>
<p>
Which should display the following output:
</p>
<!--
.........|.........|.........|.........|.........|.........|.........|.........|
-->
<pre><tt>
DEBUG: Skipping completed com.apple.main (ircii)
DEBUG: Skipping completed com.apple.fetch (ircii)
DEBUG: Executing com.apple.checksum (ircii)
--->  Checksum OK for ircii-20020912.tar.gz
DEBUG: Executing com.apple.extract (ircii)
--->  Extracting for ircii-20020912
--->  Extracting ircii-20020912.tar.gz ... DEBUG: Assembled command: 'cd /Users/
kevin/opendarwin/proj/darwinports/dports/irc/ircii/work &amp;&amp; gzip -dc /opt/local/
var/db/dports/distfiles/ircii-20020912.tar.gz | tar -xf -'
Done
</tt></pre>
<h3>
<a name="configure"></a>Running a Configure Script
</h3>
<p>
Now that the sources have been extracted into a <tt>work</tt> directory in the current working directory, we can configure the sources to compile with the desired options.  By default DarwinPorts assumes the software you're porting uses an autoconf configure script, also by default, DarwinPorts will pass the <tt>--prefix=${prefix}</tt> argument to configure, specifying that the software should be installed in the directory tree used by DarwinPorts.
</p>
<p>
ircII's standard set of options looks fine for a base install on Darwin, so we'll move on to the build phase.  
</p>

<h3>
<a name="build"></a>Building the Sources
</h3>
<p>
To build, type the following:
</p>
<pre><tt>
% port build
</tt></pre>
<p>
By default, the build phase executes the system's make(1) utility.  (This can be changed with the <tt>build.type</tt> option which accepts an argument of <tt>bsd</tt>, <tt>gnu</tt>, or <tt>pbx</tt>.  Alternatively, the <tt>build.cmd</tt> option may be used to specify an arbitrary build command.)  The above step has starting compiling the sources, when it finishes we'll be ready to install the software.  
</p>

<h3>
<a name="install"></a>Installing the Finished Product on the System
</h3>
<p>
Portfiles are required to have a <tt>contents</tt> option which specifies which files are installed.  DarwinPorts uses this information to catalog what files belong to which piece of software so they may be later uninstalled.  Each parameter to <tt>contents</tt> is a path to a file.  All paths are relative to the <tt>${prefix}</tt> variable.  As a convenient way to determine exactly what files are installed as part of ircII, let's use the find command to compose a manifest of the files in the <tt>${prefix}</tt> tree.  After installing we'll re-run the find command, and use the differences to generate our contents list.
</p>
<p>
Using the unidiff format, we'll compare the list of existing files with the new list of files, only paying attention to the lines that were added.  Since the contents paths are supposed to be relative to <tt>${prefix}</tt>, we'll pipe through <tt>sed</tt> and delete the prefix (<tt>/opt/local/</tt>), storing the result in a file named <tt>contents</tt> in our port directory.  We can do this with the following commands:
</p>
<!--
.........|.........|.........|.........|.........|.........|.........|.........|
-->
<pre><tt>
% find /opt/local > /tmp/existing.files
% sudo port install
% find /opt/local > /tmp/more.files
% diff -u /tmp/existing.files /tmp/more.files | grep ^\+\/ | \
  sed -e 's|^\+/opt/local/*||g' > contents
</tt></pre>
<p>
Now that we have a contents file in our port directory, we should edit it to begin with <tt>contents {</tt> and end with a closing <tt>}</tt>.  (It is important to note that any other process using the <tt>${prefix}</tt> tree may interfere with the accuracy of the <tt>find</tt> command.  You should audit the resulting <tt>contents</tt> file to look for any files that appear out of place, specifically some DarwinPorts temporary files such as <tt>/var/db/dports/receipts/ircii-20020912.tmp</tt>.)
It's also important to review the contents file and make sure directories are listed <i>after</i> the files they contain for the uninstall process to work correctly.
Next we should edit the Portfile to include our contents file:
</p>
<pre><tt>
include contents
</tt></pre>
<p>
If the list of files installed by the port does not extends beyond one page of an 80x24 terminal, the <tt>contents</tt> option should be included in the Portfile.
Instead of <tt>include contents</tt>, one would use:
</p>
<pre><tt>
contents    bin/irc \
            bin/irc-20020912 \
            man/man1/irc.1 \
            man/man1/ircbug.1 \
            man/man1/ircII.1 \
            man/man1
</pre></tt>
<p>
Now we have a complete portfile.  Re-run the installation step to add your port to your own registry:
</p>
<pre><tt>
% sudo port install
</tt></pre>
Which will output the following:
<pre><tt>
DEBUG: Skipping completed com.apple.main (ircii)
DEBUG: Skipping completed com.apple.fetch (ircii)
DEBUG: Skipping completed com.apple.checksum (ircii)
DEBUG: Skipping completed com.apple.extract (ircii)
DEBUG: Skipping completed com.apple.patch (ircii)
DEBUG: Skipping completed com.apple.configure (ircii)
DEBUG: Skipping completed com.apple.build (ircii)
DEBUG: Skipping completed com.apple.install (ircii)
DEBUG: Executing com.apple.registry (ircii)
--->  Adding ircii to registry, this may take a moment...
</tt></pre>

<h2>
Advanced Topics
</h2>

<h3>
<a name="targets"></a>Overriding Targets
</h3>
<p>
It's possible to override the functionality of any build target with Tcl code.  A common example is the following, which might be useful for a script without an autoconf configure script:
</p>
<pre><tt>
configure {}
</tt></pre>
<p>
In the Portfile, this will replace the functionality of the configure target, thus skipping that step.  It is also possible to execute Tcl code immediately before or after any of the standard targets.  This can be accomplished in the following manner:
</p>
<!--
.........|.........|.........|.........|.........|.........|.........|.........|
-->
<pre><tt>
post-configure {
    reinplace "s|change.this.to.a.server|irc.openprojects.net|g" \
        "${workdir}/${worksrcdir}/config.h"
}
</tt></pre>
<p>
This example replaces the occurrence of <tt>change.this.to.a.server</tt> with <tt>irc.openprojects.net</tt> in the config.h file that was generated during the preceding <tt>configure</tt> phase.  Note this is a somewhat contrived example, since the same could have been accomplished by specifying <tt>--with-default-server=irc.openprojects.net</tt> in <tt>configure.args</tt>, but the approach is generally useful when such configure arguments aren't present.
</p> 

<h3>
<a name="variants"></a>Portfile Variants
</h3>
<p>
Since Darwin 6.0 has ipv6, it would be possible to configure with the <tt>--with-ipv6</tt> option.  This can be done by adding the following option to the Portfile:
</p>
<pre><tt>
configure.args      --disable-ipv6

variant ipv6 {
    configure.args-append  --enable-ipv6
}
</tt></pre>
<p>
Now the default build will not include ipv6 support, but if the ipv6 variant is requested, ircII will have it.  Options by themselves should be thought of as an assignment operator.  Since variants may be used in combination with one another, it's good practice to only append to options instead of overwrite them.  All options may be suffixed with <tt>-append</tt> or <tt>-delete</tt> to append or delete one term from the list.  You can specify building with the ipv6 variant in the following way:
</p>
<pre><tt>
% port build +ipv6
</tt></pre>

<h2>
Appendix
</h2>

<h3>
<a name="portfilelist"></a>Portfile Listing
</h3>
<p>
The following is a complete listing of the ircII Portfile:
</p>
<pre><tt>
PortSystem 1.0
name            ircii
version         20020912
categories      irc
maintainers     kevin@opendarwin.org
master_sites    ftp://ircftp.au.eterna.com.au/pub/ircII/
checksums       md5 2ae68c015698f58763a113e9bc6852cc
configure.args  --disable-ipv6
include         contents

post-configure {
        reinplace "s|change.this.to.a.server|irc.openprojects.net|g" \
                  "${workdir}/${worksrcdir}/config.h"
}

variant ipv6 {
        configure.args-append --enable-ipv6
}
</tt></pre>

<h3>
<a name="contentslist"></a>Contents Listing
</h3>
<p>
The following is a partial listing of the ircII contents file:
</p>
<pre><tt>
contents {
bin/irc
bin/irc-20020912
... omitted ...
man/man1/irc.1
man/man1/ircbug.1
man/man1/ircII.1
man/man1
man
... omitted ...
}
</tt></pre>


<? 
	od_print_footer("en"); 
?>
