# Web-interface-for-AWS-Deadline-with-FFmpeg-plugin


### Table of Contents
* [Overview](#overview)
* [Technologies](#technologies)
* [Preview](#preview)
  - [Registration](#registration)
  - [Browsing](#browsing-shared-drive)
  - [Submit](#configure-jobs-and-submit-render)
  - [Monitor](#monitor-job-status)
  - [Email and OwnCloud](#email-notification-with-owncloud-file-sharing)
  - [Play and resubmit](#play-the-output-file-and-resubmit-jobs)
  - [Presets](#create-presets-and-manage-them)
  - [Admin](#manage-users)
* [Setup](#setup)
  - [Requirements](#requirements)
  - [Installation](#installation)

## Overview

This project provides a web interface for the AWS ThinkBox Deadline render management software, with an integrated FFmpeg plugin for media processing. The interface allows users to register, browse shared drives, configure and submit render jobs, monitor job statuses, receive email notifications with OwnCloud file sharing links, play the output file, and resubmit jobs. It also includes administrative features like managing users and creating and managing presets. The interface is built using PHP, JavaScript, HTML5, and CSS, and incorporates MariaDB for database management and Python for Deadline custom event plugins.




## Technologies

<p align="center">

  
  <img alt="PHP" src="https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=python&logoColor=white" />
  <img alt="JavaScript" src="https://img.shields.io/badge/JavaScript-323330?style=flat-square&logo=javascript&logoColor=F7DF1E" />
  <img alt="html5" src="https://img.shields.io/badge/-HTML5-E34F26?style=flat-square&logo=html5&logoColor=white" />
  <img alt="Css" src="https://img.shields.io/badge/CSS-239120?&style=flat-square&logo=css3&logoColor=white" />
  <img alt="MariaDB" src="https://img.shields.io/badge/MariaDB-003545?style=flat-square&logo=python&logoColor=white" />
  <img alt="Python" src="https://img.shields.io/badge/Python-3776AB?style=flat-square&logo=python&logoColor=white" />
  <img alt="Deadline" src="https://svgur.com/i/upC.svg" />
  <img alt="FFmpeg" src="https://svgur.com/i/uoC.svg" />
  <img alt="OwnCloud" src="https://svgshare.com/i/upD.svg" />

</p>

## Preview

<p align="center"> 

<h4 align='center'>Registration</h4>

<p align="center"> 
 
  https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/6f2fc1d9-3e49-4600-a861-c399dcf10ca2

</p>

<table>
  <tr>
    <td align="center">
      <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/f7683e0b-e4b1-44ea-8cac-aea6224b5218" alt="Registration1">
    </td>
    <td align="center">
      <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/d52f1473-e7b9-45e2-baae-98d0692893b9" alt="Registration2">
    </td>
  </tr>
</table>

<p align="center">
  <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/ef4a087b-786c-4e87-bf3a-d704db974499" alt="Forgot">
</p>


<h4 align='center'>Browsing shared drive</h4>

<p align="center"> 

  https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/aee45ed7-1137-45d1-aa1f-6d3ecc94dc63

</p>

<h4 align='center'>Configure jobs and submit render</h4>

<p align="center"> 

  https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/bc5c8d94-b654-42aa-9e83-28fa4e622ecd


  <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/8f10d4cd-bad3-4e86-bcd8-8b4d9b60dc59"/>

</p>


<h4 align='center'>Monitor job status</h4>

<p align="center"> 


  <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/d7017dcc-97fd-4fe8-af31-bd413e3eae92"/>
  <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/2017bdb5-400b-45b5-8aa0-8b8d10ada587"/>
  <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/e828e25f-b083-46f6-b5b3-b1589f41be2d"/>
</p>

<h4 align='center'>Email notification with owncloud file sharing</h4>

<p align="center"> 
  <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/5ec1ffe2-cbf1-44dd-b27d-219d31456af5"/>
  <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/7c48d2e1-2d24-48c2-a41b-f2c9f7681c8b"/>
  <img src="https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/5b5f6df3-d588-4706-add3-25857e12d90a"/>
</p>


<h4 align='center'>Play the output file and resubmit jobs</h4>

<p align="center"> 
  
  https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/649990e2-1554-4a87-ae21-1ca14e3b231a
  
</p>

<h4 align='center'>Create presets and manage them</h4>

<p align="center"> 
  
  https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/a3744a22-8f5f-499b-8ed5-efb9ead6d698
  
  https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/606bef57-2d10-47d1-a8e5-21c05de16263

</p>

<h4 align='center'>Manage users</h4>

<p align="center"> 
  
  ![ManageUsers_blur0](https://github.com/NikodemPietrzyk/Web-interface-for-AWS-Deadline-with-FFmpeg-plugin/assets/108872794/2c19bde3-0f31-47ba-87a7-c25786cca7d6)

</p>


## Setup

#### Requirements
1. AWS Thinkbox Deadline with web client as well as at leat one render node with FFmpeg plugin
2. PHPMailer
3. SQL DB
4. [Event handler for deadline](https://github.com/NikodemPietrzyk/Event-Plugin-Sending-Job-Status-For-Thinkbox-Deadline)
5. Apache or some web server

#### Installation
1. Setup config file and drop inside "../"
2. Import DB template
3. Register as a mainterter




