// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Windows.Controls;

namespace Genius.Views.Pages.Design
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
            textboxSystemName.Text = Code.GH.CurrentExpertSystem.Name;
            textboxSystemDescription.Text = Code.GH.CurrentExpertSystem.Description;

            textProductsCount.Text = Code.GH.CurrentExpertSystem.KnowledgeBase.Products.Count.ToString();
            textConditionsCount.Text = Code.GH.CurrentExpertSystem.KnowledgeBase.Conditions.Count.ToString();
            textReferencesCount.Text = Code.GH.CurrentExpertSystem.KnowledgeBase.References.Count.ToString();
        }

        private void DashboardButton_Click(object sender, System.Windows.RoutedEventArgs e)
        {
            Code.GH.Navigate((sender as MaterialWPF.Controls.MaterialButton).Tag.ToString());
        }
    }
}
