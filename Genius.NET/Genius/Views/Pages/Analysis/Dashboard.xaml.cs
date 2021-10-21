// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Windows.Controls;

namespace Genius.Views.Pages.Analysis
{
    /// <summary>
    /// Interaction logic for Dashboard.xaml
    /// </summary>
    public partial class Dashboard : Page
    {
        public Dashboard()
        {
            InitializeComponent();
            this.UpdateDashboardContent();
        }

        public void OnRequestNavigate()
        {
            this.UpdateDashboardContent();
        }

        private void UpdateDashboardContent()
        {
            textName.Text = Code.GH.CurrentExpertSystem.Name;
            textDescription.Text = Code.GH.CurrentExpertSystem.Description;

            textProductsCount.Text = Code.GH.CurrentExpertSystem.KnowledgeBase.Products.Count.ToString();
            textConditionsCount.Text = Code.GH.CurrentExpertSystem.KnowledgeBase.Conditions.Count.ToString();
            textReferencesCount.Text = Code.GH.CurrentExpertSystem.KnowledgeBase.References.Count.ToString();
        }

        private void StartButton_Click(object sender, System.Windows.RoutedEventArgs e)
        {
            Code.GH.Navigate("workflow");
        }
    }
}
