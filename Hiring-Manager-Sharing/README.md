## Hiring Manager Sharing
I was going through the Force.com Fundamentals book in preparation for the App Builder certification exam, when I came across [the section on Manual Sharing](https://developer.salesforce.com/docs/atlas.en-us.fundamentals.meta/fundamentals/adg_securing_data_manual_sharing.htm). On this page, they claim that manual sharing is the solution to the following requirements:
- Hiring managers need read and update access on position records on which they're the hiring manager.
- Hiring managers need read access on candidate records on which they're the hiring manager.

Since I was already familiar with Apex Managed Sharing, I knew right away this couldn't be the best way to handle the problem. So I took matters into my own hands.
In this repository, you'll find 2 triggers - one on the Position object and one on the Job Application object. All of the logic however is in the SharingMethods class, which is tested by the HiringManagerSharing_UT unit test class, which provides 95% code coverage. 

I ended up adding additional functionality beyond what the book states, so here is a summary of everything these triggers do:
- A position record is shared with the hiring manager on insert and when the hiring manager changes
- If the position record has related job applications, those records and related candidate records are also shared with the hiring manager
- If the hiring manager changes on a position, access to the record and all related job applications and candidates is revoked from the previous hiring manager
- Whenever a job application is created, it and its related candidate record are shared to the hiring manager on the related position
- The ability to change either the position or the candidate on the job application is prevented.
