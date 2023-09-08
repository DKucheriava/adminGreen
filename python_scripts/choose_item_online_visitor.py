import sys
import json
import pandas as pd
from functions import function_calculate_recommendation_probabilities_one_visitor, function_choose_one_item_to_display, check_item_theme_mood
from train_visitors import df_items_with_betas
##################################################
##### Choosing an item for an online visitor #####
##################################################

def function_choose_item_online_visitor():
    # Get df_items_with_betas from train_visitors script
    # get the theme and mood from php
    theme = sys.argv[1]
    mood = sys.argv[2]

    # calculate the probabilities
    df_with_final_predictions = function_calculate_recommendation_probabilities_one_visitor(df_items_with_betas, theme, mood)

    # choose one item to display
    df_results = function_choose_one_item_to_display(df_with_final_predictions)

    # Convert df_results to a JSON string
    df_results_json = df_results.to_json(orient='records')

    # Print the JSON string to the console
    print(df_results_json)

if __name__ == "__main__":
    function_choose_item_online_visitor()

# Notes
"""
1. df_interactions and df_items_with_betas should be created by the "training_users" algorithm.

2. df_users, df_items, and df_users_requesting_recommendation should be imported from the PWA

3. If you wish to read a more detailed description of each functions, see 'functions' script.
"""
