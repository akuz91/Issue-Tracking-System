# Issue_Tracking_System

The project was completed for the Principles of Databases (CS-GY-6083) course at NYU in Spring 2020. A web-based issue tracking system was devleoped and integrated with a database management system. The system allows users to create projects, set up workflow schemas, report project issues, assign issues to other users, and update the status of issues based on the project's defined workflow schema. 

The first part of the project focused on developing and implementing a database system, and the second part focused on developing the web application.

The ER model for the issue tracker is as follows:
<img height = "400" src="https://github.com/akuz91/Issue_Tracking_System/blob/master/ER_model.png" />

Using PHP and HTML, a web-based user interface was created by using a local web server, database and browser. The program was connected to the backend database and used prepared statements to return results as a web page. The general layout of the web application is shown below.

<img height="300" src="https://github.com/akuz91/Issue_Tracking_System/blob/master/web_app_layout.png" />

A user can sign in and view all projects they are involved in as well as all issues associated with a specific project. Each project has its own dashboard showing all project leaders and team members on the project, as well as a dashboard for each issue showing current status and status history. Depending on user roles, users can create new projects and new project issues. They may also assign project issues to other team members or add team members to a specific project. Details relating to user roles can be found in the project pdf.

A snapshot of the issues dashboard in the web application:
<img height="400" src="https://github.com/akuz91/Issue_Tracking_System/blob/master/issues_page.png" />

