import pandas as pd
from functions import function_calculate_recommendation_probabilities_one_user, function_choose_one_item_to_display
###############################################
##### Choosing an item for an online user #####
###############################################

# calculate the probabilities
df_with_final_predictions = function_calculate_recommendation_probabilities_one_user(df_users_requesting_recommendation, df_users_items_with_betas, theme, mood) # calculating the final predictions

# choose one item to display
df_results = function_choose_one_item_to_display(df_with_final_predictions) # choosing the item to display

# Notes
"""
1. df_interactions and df_users_items_with_betas should be created by the "training_users" algorithm.

2. df_users, df_items, and df_users_requesting_recommendation should be imported from the PWA

3. If you would like to create a df_users_requesting_recommendation for testing purposes,
you can use the script in the Github file "create_df_users_requesting_recommendation_for_testing"

4. If you wish to read a more detailed description of each functions, see 'functions' script.
"""


