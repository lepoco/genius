// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Windows;

namespace Genius
{
    /// <summary>
    /// Interaction logic for App.xaml
    /// </summary>
    public partial class App : Application
    {
        private Code.Engine.System _currentExpertSystem;
        internal Code.Engine.System CurrentExpertSystem
        {
            get
            {
#if DEBUG
                if (this._currentExpertSystem == null)
                    this._currentExpertSystem = Code.UI.Samples.SampleExpert;
                
#else
                if (this._currentExpertSystem == null)
                    this._currentExpertSystem = new Code.Engine.System();
#endif
                return this._currentExpertSystem;
            }
            set => this._currentExpertSystem = value;
        }
    }
}
