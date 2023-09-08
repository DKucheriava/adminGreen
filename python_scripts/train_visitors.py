### import packages
import pandas as pd
from functions import function_remove_rows_with_missing_values, function_calculate_probabilities_visitors
from creating_data_from_db import df_items, df_interactions

###########################################
##### Training the model for visitors #####
###########################################

""" Prepare the data for analysis """
### Prepare the interactions dataframe
# Remove rows with missing values in columns needed for the recommendation code
df_interactions = function_remove_rows_with_missing_values(df_interactions)

""" Calculate the betas """
# calculate the betas
df_items_with_betas = function_calculate_probabilities_visitors(df_interactions, df_items)

print('df_items_with_betas')

# Note
"""
The input datasets should be imported from the PWA's database (df_items, df_interactions).

"""
