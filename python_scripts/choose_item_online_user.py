import sys
import json
import random
import pandas as pd
from functions import function_calculate_recommendation_probabilities_one_user, function_choose_one_item_to_display, create_users_requesting_recommendation
from train_users import df_users, df_users_items_with_betas
###############################################
##### Choosing an item for an online user #####
###############################################

def function_choose_item_online_user():
    # Get df_users and df_items_with_betas from train_users script
    # get the theme and mood from php
    theme = sys.argv[1]
    mood = sys.argv[2]

    # Randomly choose multiple userids
    df_users_requesting_recommendation = create_users_requesting_recommendation(df_users, random.randint(1, 15))

    # calculate the probabilities
    df_with_final_predictions = function_calculate_recommendation_probabilities_one_user(df_users_requesting_recommendation, df_users_items_with_betas, theme, mood)

    # choose one item to display
    df_results = function_choose_one_item_to_display(df_with_final_predictions)
    df_results_json = df_results.to_json(orient='records')

    # Print the JSON string to the console
    print(df_results_json)

if __name__ == "__main__":
    function_choose_item_online_user()

# Notes
"""
1. df_interactions and df_users_items_with_betas should be created by the "training_users" algorithm.

2. df_users, df_items, and df_users_requesting_recommendation should be imported from the PWA

3. If you would like to create a df_users_requesting_recommendation for testing purposes,
you can use the script in the Github file "create_df_users_requesting_recommendation_for_testing"

4. If you wish to read a more detailed description of each functions, see 'functions' script.
"""


