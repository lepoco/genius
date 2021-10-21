// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using MaterialWPF.UI;
using System;
using System.Collections.ObjectModel;
using System.Windows;

namespace Genius.Views
{
    /// <summary>
    /// Interaction logic for Container.xaml
    /// </summary>
    public partial class Container : Window
    {
        private static string[] Messages = new string[] {
            "The future is now old man...",
            "I think, therefore I am...",
            "Those who can imagine anything, can create the impossible...",
            "If a machine is expected to be infallible, it cannot also be intelligent...."
        };

        public MaterialWPF.Controls.Snackbar Snackbar
        {
            get => rootSnackbar;
        }

        public Container()
        {
            InitializeComponent();

            this.InitializeNavigation();
            this.Splash();
        }

        public void InitializeNavigation()
        {
            rootNavigation.Frame = rootFrame;
            rootNavigation.Items = new ObservableCollection<NavItem>
            {
                new NavItem { Icon = MaterialIcon.GridView, Name = "Wellcome", Tag = "wellcome", Type = typeof(Pages.Wellcome)},
                new NavItem { Icon = MaterialIcon.Settings, Name = "Settings", Tag = "settings", Type = typeof(Pages.Settings)},
                new NavItem { Icon = MaterialIcon.ChatBubbles, Name = "About", Tag = "about", Type = typeof(Pages.About)}
            };
            rootNavigation.Navigate("wellcome");
        }

        private async void Splash()
        {
            mainSplash.Version = Genius.Code.GH.Version;
            mainSplash.Logo = new System.Windows.Media.Imaging.BitmapImage(new System.Uri("pack://application:,,,/Assets/genius-banner.png"));

            Random random = new Random();
            int randomMessageId = random.Next(0, Messages.Length);
            mainSplash.SubTitle = Messages[randomMessageId];

            await System.Threading.Tasks.Task.Run(() =>
            {
                System.Threading.Thread.Sleep(3000);

                App.Current.Dispatcher.Invoke(() =>
                {
                    mainSplash.Visibility = Visibility.Hidden;
                    appLogo.Visibility = Visibility.Visible;
                });
            });
        }
    }
}
