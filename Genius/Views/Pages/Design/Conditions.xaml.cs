// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Windows;
using System.Windows.Controls;

namespace Genius.Views.Pages.Design
{
    /// <summary>
    /// Interaction logic for Conditions.xaml
    /// </summary>
    public partial class Conditions : Page
    {
        public Conditions()
        {
            InitializeComponent();
            dialogAddCondition.ActionButtonGlyph = MaterialWPF.UI.MiconIcons.CalculatorAddition;
            dialogAddCondition.ActionButtonClick = DialogAdd_Click;

            this.UpdateList();
        }

        public void OnRequestNavigate()
        {
            
        }

        private void UpdateList()
        {
            listConditions.ItemsSource = null;
            listConditions.ItemsSource = Code.GH.CurrentExpertSystem.KnowledgeBase.Conditions;
        }

        private void DialogAdd_Click()
        {
            if (string.IsNullOrEmpty(textboxConditionName.Text))
            {
                Code.GH.GlobalSnackbar.Header = "An error occured!";
                Code.GH.GlobalSnackbar.Message = "The new condition must have a name.";
                Code.GH.GlobalSnackbar.Show();
                return;
            }

            Code.GH.CurrentExpertSystem.KnowledgeBase.AddCondition(textboxConditionName.Text, textboxConditionDescription.Text);
            
            textboxConditionName.Text = textboxConditionDescription.Text = "";
            dialogAddCondition.Hide();
            this.UpdateList();
        }

        private void AddButton_Click(object sender, RoutedEventArgs e)
        {
            dialogAddCondition.Show();
        }
    }
}
