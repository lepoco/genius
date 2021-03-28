// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System.Collections.Generic;

namespace Genius.Code.Engine.Interfaces
{
    public interface IExpertDatabase //<T> where T : IExpertElement
    {
        public List<Engine.Product> Products { get; set; }
        public List<Engine.Condition> Conditions { get; set; }
        public List<Engine.Reference> References { get; set; }
    }
}
