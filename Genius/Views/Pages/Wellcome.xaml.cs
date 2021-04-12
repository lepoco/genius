// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Collections.Generic;
using System.Windows.Controls;

namespace Genius.Views.Pages
{
    public struct RecentProject
    {
        public string Name { get; set; }
        public string Path { get; set; }
        public string Date { get; set; }
    }
    /// <summary>
    /// Interaction logic for Wellcome.xaml
    /// </summary>
    public partial class Wellcome : Page
    {
        public Wellcome()
        {
            InitializeComponent();
        }

        public void OnNavigationRequest()
        {
            this.LoadRecentProjects();
        }

        public void LoadRecentProjects()
        {
            List<RecentProject> recentProjects = new List<RecentProject> {};


            recentProjects.Add(new RecentProject
            {
                Name = "Sample Expert System",
                Path = @"C:\Users\Raven\Documents\Genius\Sample.genius",
                Date = "2020.03.04 15:54"
            });

            recentProjects.Add(new RecentProject
            {
                Name = "Disease diagnosis system",
                Path = @"C:\Users\Raven\Documents\Genius\Disease.genius",
                Date = "2020.03.01 16:31"
            });


            recentProjects.Add(new RecentProject
            {
                Name = "Testtest Test",
                Path = @"C:\Users\Raven\Documents\Genius\123.genius",
                Date = "2020.01.04 11:57"
            });


            listProjects.ItemsSource = recentProjects;
            DataContext = this;
        }

        private void ButtonDesign_Click(object sender, System.Windows.RoutedEventArgs e)
        {
            Code.GH.Navigation.Items = new System.Collections.ObjectModel.ObservableCollection<MaterialWPF.UI.NavItem>
            {
                new MaterialWPF.UI.NavItem { Icon = MaterialWPF.UI.MaterialIcon.Communications, Name = "Dashboard", Tag = "dashboard", Type = typeof(Pages.Design.Dashboard)},
                new MaterialWPF.UI.NavItem { Icon = MaterialWPF.UI.MaterialIcon.CheckList, Name = "Conditions", Tag = "conditions", Type = typeof(Pages.Design.Conditions)},
                new MaterialWPF.UI.NavItem { Icon = MaterialWPF.UI.MaterialIcon.DialShape1, Name = "Products", Tag = "products", Type = typeof(Pages.Design.Products)},
                new MaterialWPF.UI.NavItem { Icon = MaterialWPF.UI.MaterialIcon.Connected, Name = "References", Tag = "references", Type = typeof(Pages.Design.References)}
            };
            Code.GH.Navigation.Footer = new System.Collections.ObjectModel.ObservableCollection<MaterialWPF.UI.NavItem>
            {
                new MaterialWPF.UI.NavItem { Icon = MaterialWPF.UI.MaterialIcon.Back, Name = "Return", Tag = "back", Action = BackToWellcome},
            };

            Code.GH.Navigation.Navigate("dashboard");
        }

        private void ButtonAnalysis_Click(object sender, System.Windows.RoutedEventArgs e)
        {
            Code.GH.Navigation.Items = new System.Collections.ObjectModel.ObservableCollection<MaterialWPF.UI.NavItem>
            {
                new MaterialWPF.UI.NavItem { Icon = MaterialWPF.UI.MaterialIcon.DialShape1, Name = "Dashboard", Tag = "dashboard", Type = typeof(Pages.Analysis.Dashboard)},
                new MaterialWPF.UI.NavItem { Icon = MaterialWPF.UI.MaterialIcon.Diagnostic, Name = "Workflow", Tag = "workflow", Type = typeof(Pages.Analysis.Workflow)}
            };
            Code.GH.Navigation.Navigate("dashboard");
            Code.GH.Navigation.Footer = new System.Collections.ObjectModel.ObservableCollection<MaterialWPF.UI.NavItem>
            {
                new MaterialWPF.UI.NavItem { Icon = MaterialWPF.UI.MaterialIcon.Back, Name = "Return", Tag = "back", Action = BackToWellcome},
            };
        }


        private void BackToWellcome()
        {
            (Code.GH.App.MainWindow as Container).InitializeNavigation();
            //Code.GH.Navigation.Footer = null;
        }
    }
}
