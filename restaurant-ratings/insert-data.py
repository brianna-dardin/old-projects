from sqlalchemy import create_engine
import pandas as pd

# create database connection
engine = create_engine('mysql://user:password@localhost:3306/restaurant_ratings?charset=utf8')

"""
RESTAURANT DATA
"""

# open restaurant data file
restaurant_data = pd.read_csv(r'geoplaces2.csv', na_values=["?"])

# drop unwanted columns
restaurant_data = restaurant_data.drop(['the_geom_meter','fax','country','url'],axis=1)

# convert franchise column into binary column
restaurant_data['franchise'] = restaurant_data['franchise'].map({'t':1,'f':0})

# grab restaurant parking data
parking_data = pd.read_csv(r'chefmozparking.csv')

# remove rows that don't refer to restaurants in our data
rest_ids = restaurant_data['placeID'].unique()
parking_data = parking_data[parking_data['placeID'].isin(rest_ids)]

# append parking column to our original dataframe
restaurant_data = restaurant_data.join(parking_data.set_index(['placeID']), on=['placeID'])

# insert data into RESTAURANT table
restaurant_data.to_sql(name='restaurant',con=engine,if_exists='append',index=False)

"""
RESTAURANT_HOURS DATA
"""

# open restaurant hours data file
hours_data = pd.read_csv(r'chefmozhours4.csv')

# remove duplicate rows
hours_data = hours_data.drop_duplicates()

# make sure we only insert rows for restaurants we have data for
hours_data = hours_data[hours_data['placeID'].isin(rest_ids)]

# convert Mon;Tue;Wed;Thu;Fri; values into Weekday values
hours_data['days'] = hours_data['days'].map({'Mon;Tue;Wed;Thu;Fri;':'Weekdays',
          'Sat;':'Sat','Sun;':'Sun'})

# replace any remaining semicolons in the data cause they're ugly lol
hours_data['hours'] = hours_data['hours'].str.replace(";","")

# insert data into RESTAURANT_HOURS table
hours_data.to_sql(name='restaurant_hours',con=engine,if_exists='append',index=False)

"""
CUSTOMER DATA
"""

# open customer data file
customer_data = pd.read_csv(r'userprofile.csv', na_values=["?"])

# remove the U from the userID field so they can be stored as integers
customer_data['userID'] = customer_data['userID'].str.replace("U","")

# convert smoker column into binary column
customer_data['smoker'] = customer_data['smoker'].map({True:1,False:0})

# insert data into CUSTOMER table
customer_data.to_sql(name='customer',con=engine,if_exists='append',index=False)

"""
REVIEW DATA
"""

# open review data file
rating_data = pd.read_csv(r'rating_final.csv')

# remove the U from the userID field so they can be stored as integers
rating_data['userID'] = rating_data['userID'].str.replace("U","")

# insert data into RATING table
rating_data.to_sql(name='rating',con=engine,if_exists='append',index=False)

"""
CUISINE DATA
"""

# open customer cuisine file - starting with the customer file because it contains
# more cuisine values than the restaurant one
cust_cuisine_data = pd.read_csv(r'usercuisine.csv')

# extract all the distinct values for cuisine and create a new dataframe for them
cuisine_data = pd.DataFrame(data=cust_cuisine_data['Rcuisine'].unique(),columns=['cuisine'])

# add index column to dataframe, add 1 to each value because 0 is not a valid ID
cuisine_data['cuisineID'] = cuisine_data.index
cuisine_data['cuisineID'] += 1

# insert data into CUISINE table
cuisine_data.to_sql(name='cuisine',con=engine,if_exists='append',index=False)

"""
CUSTOMER_CUISINE DATA
"""

# remove the U from the userID field so they can be stored as integers
cust_cuisine_data['userID'] = cust_cuisine_data['userID'].str.replace("U","")

# iterate through the data to lookup the ID values for the cuisines, then create a new
# dataframe composed only of the customer and cuisine IDs with no description fields
new_cust_cui_data = []
for index, row in cust_cuisine_data.iterrows():
    cuisineID = cuisine_data.loc[cuisine_data['cuisine'] == row['Rcuisine'], 'cuisineID'].item()
    new_cust_cui_data.append([row['userID'],cuisineID])
    
cust_cuisine_data = pd.DataFrame(data=new_cust_cui_data,columns=['userID','cuisineID'])

# insert data into CUSTOMER_CUISINE table
cust_cuisine_data.to_sql(name='customer_cuisine',con=engine,if_exists='append',index=False)

"""
RESTAURANT_CUISINE DATA
"""

# open restaurant cuisine file
rest_cuisine_data = pd.read_csv(r'chefmozcuisine.csv')

# remove rows that don't refer to restaurants in our data
rest_cuisine_data = rest_cuisine_data[rest_cuisine_data['placeID'].isin(rest_ids)]

# iterate through the data to lookup the ID values for the cuisines, then create a new
# dataframe composed only of the restaurant and cuisine IDs with no description fields
new_rest_cui_data = []
for index, row in rest_cuisine_data.iterrows():
    cuisineID = cuisine_data.loc[cuisine_data['cuisine'] == row['Rcuisine'], 'cuisineID'].item()
    new_rest_cui_data.append([row['placeID'],cuisineID])

rest_cuisine_data = pd.DataFrame(data=new_rest_cui_data,columns=['placeID','cuisineID'])

# insert data into RESTAURANT_CUISINE table
rest_cuisine_data.to_sql(name='restaurant_cuisine',con=engine,if_exists='append',index=False)

"""
PAYMENT DATA
"""

# open restaurant payment file - starting with the restaurant file because it contains
# more payment values than the customer one
rest_payment_data = pd.read_csv(r'chefmozaccepts.csv')

# extract all the distinct values for payments and create a new dataframe for them
payment_data = pd.DataFrame(data=rest_payment_data['Rpayment'].unique(),columns=['payment'])

# add index column to dataframe, add 1 to each value because 0 is not a valid ID
payment_data['paymentID'] = payment_data.index
payment_data['paymentID'] += 1

# insert data into PAYMENT table
payment_data.to_sql(name='payment',con=engine,if_exists='append',index=False)

"""
RESTAURANT_PAYMENT DATA
"""

# remove rows that don't refer to restaurants in our data
rest_payment_data = rest_payment_data[rest_payment_data['placeID'].isin(rest_ids)]

# iterate through the data to lookup the ID values for the payments, then create a new
# dataframe composed only of the restaurant and payment IDs with no description fields
new_rest_pay_data = []
for index, row in rest_payment_data.iterrows():
    paymentID = payment_data.loc[payment_data['payment'] == row['Rpayment'], 'paymentID'].item()
    new_rest_pay_data.append([row['placeID'],paymentID])

rest_payment_data = pd.DataFrame(data=new_rest_pay_data,columns=['placeID','paymentID'])

# insert data into RESTAURANT_PAYMENT table
rest_payment_data.to_sql(name='restaurant_payment',con=engine,if_exists='append',index=False)

"""
CUSTOMER_PAYMENT DATA
"""

# open customer payment file
cust_payment_data = pd.read_csv(r'userpayment.csv')

# remove the U from the userID field so they can be stored as integers
cust_payment_data['userID'] = cust_payment_data['userID'].str.replace("U","")

# dataframe composed only of the customer and payment IDs with no description fields
new_cust_pay_data = []
for index, row in cust_payment_data.iterrows():
    paymentID = payment_data.loc[payment_data['payment'] == row['Upayment'], 'paymentID'].item()
    new_cust_pay_data.append([row['userID'],paymentID])

cust_payment_data = pd.DataFrame(data=new_cust_pay_data,columns=['userID','paymentID'])

# insert data into CUSTOMER_PAYMENT table
cust_payment_data.to_sql(name='customer_payment',con=engine,if_exists='append',index=False)