import sys
import json
import random
import pandas as pd
from creating_data_from_db import df_users, df_items, df_interactions
from functions import function_calculate_probabilities_users, function_choose_items_to_display_for_multiple_users, create_users_requesting_recommendation

###################################################
##### Choosing an item for many offline users #####
###################################################

def function_choose_items_offline_users():
    # calculate the betas
    df_users_items_with_betas = function_calculate_probabilities_users(df_users, df_items, df_interactions)

    # Randomly choose multiple userids
    df_users_requesting_recommendation = create_users_requesting_recommendation(df_users, random.randint(1, 15))

    # calculate the probabilities and choose an item for each user, then show all choices in a dataframe
    df_results = function_choose_items_to_display_for_multiple_users(df_users_items_with_betas, df_users_requesting_recommendation)

    # Convert df_results to a JSON string
    df_results_json = df_results.to_json(orient='records')

    # Print the JSON string to the console
    print(df_results_json)

if __name__ == "__main__":
    function_choose_items_offline_users()


# Notes
"""
1. Run this script right after a scheduled run of the "training_users" script.

2. df_users_items_with_betas and df_interactions should be created by the "training_users" algorithm.

3. df_users, df_items, and df_users_requesting_recommendation should be imported from the PWA

4. If you would like to create a df_users_requesting_recommendation for testing purposes,
you can use the script in the Github file "create_df_users_requesting_recommendation_for_testing"

5. If you wish to read a more detailed description of each functions, see 'functions' script.
"""
