The task is split into two sections and is designed as follows:
1. Back end section:
Write a REST API based invitation system that allows for the following actions:
- One user aka the Sender can send an invitation to another user aka the Invited.
- The Sender can cancel a sent invitation.
- The Invited can either accept or decline an invitation.
- The Sender can see a list of all invitations they have sent.
- The Invited can see a list of all invitations they have received.
All endpoint responses must be in JSON.
The project must include tests written in the PHPUnit framework to demonstrate how the
various API endpoints behave in relation to each other. Complete the project using Symfony3.
2. Client section:
Please use any front end framework to implement functional pages which are connected to the
back end APIs you have created.
The front end should show:
- A list of all invitations a user has sent. Each invitation should show a status which is
either “accepted” or “cancelled”
- A list of all invitations a user has received. Each invitation should have the functionality
to either “delete” or “accept”
- A search functionality so that users can search for certain invitations in the front end
