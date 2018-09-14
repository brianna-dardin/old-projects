# Restaurant Ratings Database

This repository is based on UCI Machine Learning's [Restaurant Data with Consumer Ratings](https://www.kaggle.com/uciml/restaurant-data-with-consumer-ratings) dataset. The dataset is composed of several separate CSV files containing data about restaurants, customers and customer ratings of restaurants. I decided to use this data to create a MySQL database.

****Create-Tables.sql**** - This file is basically what it says on the tin: all of the tables are defined here using MySQL-specific syntax. In the future I may write different versions of this file for different RDBMSs. 

****insert-data.py**** - I made a few decisions in the previous file that made it impossible for me to simply load all the CSVs into the tables and call it a day. Therefore, I used the pandas library to manipulate the data before loading the resulting dataframes into the MySQL tables.

# Website

After creating the MySQL database, I created a website that queries the database and displays it. (I did not develop any functionality for updating the database as this is a preexisting dataset). The querying is done through a combination of a jQuery ajax call and a PHP web service. You can view the live website [here](http://briannadardin.gear.host/ratings/). 

I used the following libraries, frameworks and resources to build the website:

- jQuery
- Bootstrap
- [DataTables Plugin](https://datatables.net/)
- [Sandstone CSS Theme](https://bootswatch.com/sandstone/)
- [Modern Business Template](https://startbootstrap.com/template-overviews/modern-business/)

# Future Extension

I plan on developing a statistical analysis on the data here and writing it up in a Jupyter Notebook. I haven't decided on which topic to pursue yet however.
